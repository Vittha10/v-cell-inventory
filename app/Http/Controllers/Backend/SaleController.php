<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    public function AllSales(){
        $allData = Sale::orderBy('id','desc')->get();
        return view('admin.backend.sales.all_sales',compact('allData'));
    }

    public function AddSales(){
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.sales.add_sales',compact('customers','warehouses'));
    }


     public function StoreSales(Request $request){

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

    try {

        DB::beginTransaction();

        $grandTotal = 0;

        $sales = Sale::create([
            'date' => $request->date,
            'warehouse_id' => $request->warehouse_id,
            'customer_id' => $request->customer_id,
            'discount' => $request->discount ?? 0,
            'shipping' => $request->shipping ?? 0,
            'status' => $request->status,
            'note' => $request->note,
            'grand_total' => 0,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->due_amount,

        ]);

    foreach($request->products as $productData){
        $product = Product::findOrFail($productData['id']);
        $netUnitCost = $productData['net_unit_cost'] ?? $product->price;

        if ($netUnitCost === null) {
            throw new \Exception("Net Unit cost is missing ofr the product id" . $productData['id']);
        }

        $subtotal = ($netUnitCost * $productData['quantity']) - ($productData['discount'] ?? 0);
        $grandTotal += $subtotal;

        SaleItem::create([
            'sale_id' => $sales->id,
            'product_id' => $productData['id'],
            'net_unit_cost' => $netUnitCost,
            'stock' => $product->product_qty + $productData['quantity'],
            'quantity' => $productData['quantity'],
            'discount' => $productData['discount'] ?? 0,
            'subtotal' => $subtotal,
        ]);

        $product->decrement('product_qty', $productData['quantity']);
    }

    $sales->update(['grand_total' => $grandTotal + $request->shipping - $request->discount]);

    DB::commit();

    $notification = array(
        'message' => 'Sales Stored Successfully',
        'alert-type' => 'success'
     );
     return redirect()->route('all.sale')->with($notification);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
      }
    }

    public function EditSales($id){
        $editData = Sale::with('saleItems.product')->findOrFail($id);
        $customers = Customer::all();
        $warehouses = WareHouse::all();
        return view('admin.backend.sales.edit_sales',compact('editData','customers','warehouses'));
    }

    public function UpdateSales(Request $request, $id){

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

        $sales = Sale::findOrFail($id);
        $sales->update([
            'date' => $request->date,
            'warehouse_id' => $request->warehouse_id,
            'customer_id' => $request->customer_id,
            'discount' => $request->discount ?? 0,
            'shipping' => $request->shipping ?? 0,
            'status' => $request->status,
            'note' => $request->note,
            'grand_total' => $request->grand_total,
            'paid_amount' => $request->paid_amount,
            'due_amount' => $request->due_amount,
            'full_paid' => $request->full_paid,
        ]);
    SaleItem::where('sale_id',$sales->id)->delete();

    foreach($request->products as $product_id => $product){
        SaleItem::create([
            'sale_id' => $sales->id,
            'product_id' => $product_id,
            'net_unit_cost' => $product['net_unit_cost'],
            'stock' => $product['stock'],
            'quantity' => $product['quantity'],
            'discount' => $product['discount'] ?? 0,
            'subtotal' => $product['subtotal'],
        ]);
        $productModel = Product::find($product_id);
        if ($productModel) {
            $productModel->product_qty += $product['quantity'];
            $productModel->save();
        }
    }

    $notification = array(
        'message' => 'Sale Updated Successfully',
        'alert-type' => 'success'
     );
     return redirect()->route('all.sale')->with($notification);
    }

    public function DeleteSales($id){
        try {
          DB::beginTransaction();
          $sales = Sale::findOrFail($id);
          $SalesItems = SaleItem::where('sale_id',$id)->get();

          foreach($SalesItems as $item){
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('product_qty',$item->quantity);
            }
          }
          SaleItem::where('sale_id',$id)->delete();
          $sales->delete();
          DB::commit();

          $notification = array(
            'message' => 'Sale Deleted Successfully',
            'alert-type' => 'success'
         );
         return redirect()->route('all.sale')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
          }
    }
    public function DetailsSales($id){
        $sales = Sale::with(['customer','saleItems.product'])->find($id);
        return view('admin.backend.sales.sales_details',compact('sales'));

    }

     public function InvoiceSales($id){
        $sales = Sale::with(['customer','warehouse','saleItems.product'])->find($id);

        $pdf = Pdf::loadView('admin.backend.sales.invoice_pdf',compact('sales'));
        return $pdf->download('sales_'.$id.'.pdf');

    }

    public function PurchaseProductSearch(Request $request)
    {
        $query = $request->get('search');

        $products = Product::where('product_name', 'LIKE', "%{$query}%")
                            ->orWhere('product_code', 'LIKE', "%{$query}%")
                            ->limit(5)
                            ->get();

        $output = '<div class="shadow-sm list-group">';
        if (count($products) > 0) {
            foreach ($products as $row) {
                $output .= '<a href="javascript:void(0)" class="list-group-item list-group-item-action"
                               onclick="selectProduct('.$row->id.', \''.addslashes($row->product_name).'\', '.$row->selling_price.', '.$row->product_qty.')">
                                <b>'.$row->product_name.'</b> ('.$row->product_code.') - <span class="text-success">Rp '.number_format($row->selling_price).'</span>
                            </a>';
            }
        } else {
            $output .= '<div class="list-group-item text-danger">Produk tidak ditemukan</div>';
        }
        $output .= '</div>';
        return $output;
    }
}
