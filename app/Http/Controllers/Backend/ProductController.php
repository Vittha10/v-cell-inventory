<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;
use App\Models\Supplier;
use App\Models\Brand;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    /* ========================= MANAJEMEN KATEGORI ========================= */

    public function AllCategory(){
        $category = ProductCategory::latest()->get();
        return view('admin.backend.category.all_category',compact('category'));
    }

    public function StoreCategory(Request $request){
        ProductCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)), 
        ]);
        $notification = array('message' => 'Kategori Berhasil Ditambah','alert-type' => 'success'); 
        return redirect()->back()->with($notification);
    }

    public function EditCategory($id){
        $category = ProductCategory::find($id);
        return response()->json($category);
    }

    public function UpdateCategory(Request $request){
        $cat_id = $request->cat_id;
        ProductCategory::find($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ','-',$request->category_name)), 
        ]);
        $notification = array('message' => 'Kategori Berhasil Diupdate','alert-type' => 'success'); 
        return redirect()->back()->with($notification);
    }

    public function DeleteCategory($id){
        ProductCategory::find($id)->delete();
        $notification = array('message' => 'Kategori Berhasil Dihapus','alert-type' => 'success'); 
        return redirect()->back()->with($notification);
    }

    /* ========================= MANAJEMEN PRODUK ========================= */

    public function AllProduct(){
        $allData = Product::orderBy('id','desc')->get();
        return view('admin.backend.product.product_list',compact('allData'));
    }

    public function AddProduct(){
        $categories = ProductCategory::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.product.add_product',compact('categories','brands','suppliers','warehouses')); 
    }

    public function StoreProduct(Request $request){
        $request->validate([
        'code' => 'required|unique:products,code', 
        'name' => 'required',
    ], [
        'code.unique' => 'Kode SKU ini sudah digunakan oleh produk lain!',
        'code.required' => 'Kode SKU tidak boleh kosong!',
    ]);
        $product = Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'price' => $request->price,
            'stock_alert' => $request->stock_alert,
            'note' => $request->note,
            'product_qty' => $request->product_qty,
            'status' => $request->status,
            'created_at' => now(), 
        ]);

        if ($request->hasFile('image')) {
           foreach($request->file('image') as $img) {
               $manager = new ImageManager(new Driver());
               $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
               $imgs = $manager->read($img);
               $imgs->resize(150,150)->save(public_path('upload/productimg/'.$name_gen));
               ProductImage::create([
                'product_id' => $product->id,
                'image' => 'upload/productimg/'.$name_gen
               ]);
           }
        }
        $notification = array('message' => 'Produk Berhasil Disimpan','alert-type' => 'success'); 
        return redirect()->route('all.product')->with($notification);
    }

    public function EditProduct($id){
        $editData = Product::find($id);
        $categories = ProductCategory::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $warehouses = WareHouse::all();
        $multiimg = ProductImage::where('product_id',$id)->get();
        return view('admin.backend.product.edit_product',compact('categories','brands','suppliers','warehouses','editData','multiimg')); 
    }

    public function UpdateProduct(Request $request){
        $pro_id = $request->id;
        $product = Product::findOrFail($pro_id);
        $product->update([
            'name' => $request->name,
            'code' => $request->code,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => $request->price,
            'stock_alert' => $request->stock_alert,
            'note' => $request->note,
            'warehouse_id' => $request->warehouse_id,
            'supplier_id' => $request->supplier_id,
            'product_qty' => $request->product_qty,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image')) {
            foreach($request->file('image') as $img) {
                $manager = new ImageManager(new Driver());
                $name_gen = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
                $imgs = $manager->read($img);
                $imgs->resize(150,150)->save(public_path('upload/productimg/'.$name_gen)); 
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'upload/productimg/'.$name_gen
                ]); 
            }
        }
        $notification = array('message' => 'Produk Berhasil Diupdate','alert-type' => 'success'); 
        return redirect()->route('all.product')->with($notification); 
    }

    public function DeleteProduct($id){
        $product = Product::findOrFail($id);
        $images = ProductImage::where('product_id',$id)->get();
        foreach($images as $img){
            if (file_exists(public_path($img->image))) {
                unlink(public_path($img->image));
            }
        }
        ProductImage::where('product_id',$id)->delete();
        $product->delete();
        $notification = array('message' => 'Produk Berhasil Dihapus','alert-type' => 'success'); 
        return redirect()->back()->with($notification);
    }

    /* ========================= STOCK OPNAME (FITUR BARU) ========================= */

    // List Riwayat Stock Opname (Halaman Depan)
    public function StockOpname(){
        $stock_history = DB::table('stock_opnames')
                        ->join('products', 'stock_opnames.product_id', '=', 'products.id')
                        ->select('stock_opnames.*', 'products.name as product_name')
                        ->orderBy('id','desc')
                        ->get();

        return view('admin.backend.product.stock_opname_list', compact('stock_history'));
    }

    // Form Tambah Stock Opname
    public function AddStockOpname(){
        $products = Product::latest()->get();
        return view('admin.backend.product.stock_opname_add', compact('products'));
    }

    // Simpan Data Stock Opname
    public function StoreStockOpname(Request $request){
        $product = Product::findOrFail($request->product_id);
        
        $stok_sistem = $product->product_qty; 
        $qty_tambah = $request->qty_tambah ?? 0;
        $qty_kurang = $request->qty_kurang ?? 0;
        $stok_fisik_akhir = $stok_sistem + $qty_tambah - $qty_kurang;

        DB::table('stock_opnames')->insert([
            'product_id' => $request->product_id,
            'tanggal_so' => $request->tanggal_so,
            'stok_sistem' => $stok_sistem,
            'qty_tambah' => $qty_tambah,
            'qty_kurang' => $qty_kurang,
            'stok_fisik' => $stok_fisik_akhir,
            'selisih' => $stok_fisik_akhir - $stok_sistem,
            'alasan' => $request->alasan,
            'status' => $request->status,
            'created_at' => now(),
        ]);

        // Stok Produk terupdate otomatis jika status 'Approved'
        if ($request->status == 'Approved') {
            $product->update([
                'product_qty' => $stok_fisik_akhir
            ]);
        }

        $notification = array('message' => 'Stock Opname Berhasil Disimpan!','alert-type' => 'success');
        return redirect()->route('stock.opname')->with($notification);

    }

    /* ========================= DETAIL PRODUK ========================= */

    public function DetailsProduct($id){
        // Mengambil data produk berdasarkan ID
        $product = Product::findOrFail($id);
        
        // Mengambil galeri foto produk tersebut
        $multiimg = ProductImage::where('product_id', $id)->get();
        
        // Mengambil data pendukung lainnya (opsional, sesuaikan dengan kebutuhan view Anda)
        $categories = ProductCategory::all();
        $brands = Brand::all();
        
        return view('admin.backend.product.details_product', compact('product', 'multiimg', 'categories', 'brands'));
    }
    
    public function DeleteStockOpname($id){
    // Menghapus data dari tabel stock_opnames berdasarkan ID
    DB::table('stock_opnames')->where('id', $id)->delete();

    $notification = array(
        'message' => 'Data Stock Opname Berhasil Dihapus',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
}

// 1. Fungsi Tampilan Edit
public function EditStockOpname($id){
    $products = Product::latest()->get();
    $stock_opname = DB::table('stock_opnames')->where('id', $id)->first();
    return view('admin.backend.product.stock_opname_edit', compact('products', 'stock_opname'));
}

// 2. Fungsi Proses Update
public function UpdateStockOpname(Request $request){
    $so_id = $request->id;
    $product = Product::findOrFail($request->product_id);
    
    $qty_tambah = $request->qty_tambah ?? 0;
    $qty_kurang = $request->qty_kurang ?? 0;
    $stok_fisik_akhir = $request->stok_sistem + $qty_tambah - $qty_kurang;

    DB::table('stock_opnames')->where('id', $so_id)->update([
        'product_id' => $request->product_id,
        'tanggal_so' => $request->tanggal_so,
        'qty_tambah' => $qty_tambah,
        'qty_kurang' => $qty_kurang,
        'stok_fisik' => $stok_fisik_akhir,
        'selisih'    => $stok_fisik_akhir - $request->stok_sistem,
        'alasan'     => $request->alasan ?? '-', // Mencegah error 'alasan' cannot be null
        'status'     => $request->status,
        'updated_at' => now(),
    ]);

    if ($request->status == 'Approved') {
        $product->update(['product_qty' => $stok_fisik_akhir]);
    }

    $notification = array('message' => 'Stock Opname Berhasil Diupdate','alert-type' => 'success');
    return redirect()->route('stock.opname')->with($notification);
}


}

