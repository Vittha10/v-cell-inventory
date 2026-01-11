<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WareHouse;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transfer;
use App\Models\TransferItem;

class TransferController extends Controller
{
    public function AllTransfer(){
        $allData = Transfer::with(['transferItems.product'])->orderBy('id','desc')->get();
        return view('admin.backend.transfer.all_transfer',compact('allData'));
    }


    public function AddTransfer(){
        $warehouses = WareHouse::all();
        return view('admin.backend.transfer.add_transfer',compact('warehouses'));
    }


    public function StoreTransfer(Request $request){

        $request->validate([
            'date' => 'required|date',
            'status' => 'required',
        ]);

    try {

        DB::beginTransaction();

        $transfer = Transfer::create([
            'date' => $request->date,
            'from_warehouse_id' => $request->from_warehouse_id,
            'to_warehouse_id' => $request->to_warehouse_id,
            'discount' => $request->discount ?? 0,
            'shipping' => $request->shipping ?? 0,
            'status' => $request->status,
            'note' => $request->note,
            'grand_total' => 0,

        ]);


    foreach($request->products as $productData){
        $product = Product::findOrFail($productData['id']);
        $netUnitCost = $product->price;
        $quantity = $productData['quantity'];
        $discount = $productData['discount'];
        $subtotal = ($netUnitCost * $quantity) - $discount;

        TransferItem::create([
            'transfer_id' => $transfer->id,
            'product_id' => $productData['id'],
            'net_unit_cost' => $netUnitCost,
            'stock' => $product->product_qty,
            'quantity' => $quantity,
            'discount' => $discount,
            'subtotal' => $subtotal,
        ]);


        Product::where('id',$productData['id'])
            ->where('warehouse_id', $request->from_warehouse_id)
            ->decrement('product_qty',$quantity);



        $existingProduct = Product::where('name',$product->name)
            ->where('brand_id', $product->brand_id)
            ->where('warehouse_id', $request->to_warehouse_id)
            ->first();

        if ($existingProduct) {
            $existingProduct->increment('product_qty',$quantity);
        } else {

            Product::create([
                'name' => $product->name,
                'brand_id' => $product->brand_id,
                'warehouse_id' => $request->to_warehouse_id,
                'price' => $product->price,
                'product_qty' => $quantity,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

    }

    DB::commit();

    $notification = array(
        'message' => 'Transfer Complete Successfully',
        'alert-type' => 'success'
     );
     return redirect()->route('all.transfer')->with($notification);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
      }
    }


    public function EditTransfer($id){
        $editData = Transfer::with(['fromWarehouse','toWarehouse','transferItems.product'])->findOrFail($id);
        $warehouses = WareHouse::all();
        return view('admin.backend.transfer.edit_transfer',compact('warehouses','editData'));

    }


    public function UpdateTransfer(Request $request, $id){

        try {

         DB::beginTransaction();

         $transfer = Transfer::findOrFail($id);


         $oldTransferItems = TransferItem::where('transfer_id', $transfer->id)->get();

         foreach($oldTransferItems as $oldItem){
            Product::where('id',$oldItem->product_id)
                ->where('warehouse_id',$transfer->from_warehouse_id)
                ->increment('product_qty',$oldItem->quantity);

            Product::where('id',$oldItem->product_id)
            ->where('warehouse_id',$transfer->to_warehouse_id)
            ->decrement('product_qty',$oldItem->quantity);



            TransferItem::where('transfer_id',$transfer->id)->delete();


            $transfer->update([
            'date' => $request->date,
            'discount' => $request->discount ?? 0,
            'shipping' => $request->shipping ?? 0,
            'status' => $request->status,
            'note' => $request->note,
            'grand_total' => $request->grand_total,
            ]);


          foreach($request->products as $productId => $productData){
            $product = Product::find($productId);
            if (!$product) {
                throw new \Exception("Product id not found");
            }

            $transferItem = TransferItem::create([
                'transfer_id' => $transfer->id,
                'product_id' => $productId,
                'net_unit_cost' => $product->price ?? 0,
                'stock' => $product->product_qty,
                'quantity' => $productData['quantity'],
                'discount' => $productData['discount'] ?? 0,
                'subtotal' => $productData['subtotal'] ?? 0,
            ]);

            Product::where('id',$productId)
            ->where('warehouse_id',$transfer->from_warehouse_id)
            ->decrement('product_qty',$productData['quantity']);


            Product::where('warehouse_id',$transfer->to_warehouse_id)
            ->increment('product_qty',$productData['quantity']);

          }

          DB::commit();

            $notification = array(
                'message' => 'Transfer Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.transfer')->with($notification);
         }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
          }

    }


     public function DeleteTransfer($id){

        try {
          DB::beginTransaction();
          $transfer = Transfer::findOrFail($id);
          $transferItems = TransferItem::where('transfer_id',$transfer->id)->get();

          foreach($transferItems as $item){
            Product::where('id',$item->product_id)
            ->where('warehouse_id',$transfer->from_warehouse_id)
            ->increment('product_qty',$item->quantity);


            Product::where('warehouse_id',$transfer->to_warehouse_id)
            ->decrement('product_qty',$item->quantity);


          }
          TransferItem::where('transfer_id',$transfer->id)->delete();
          $transfer->delete();
          DB::commit();

          $notification = array(
            'message' => 'Transfer Deleted Successfully',
            'alert-type' => 'success'
         );
         return redirect()->route('all.transfer')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
          }
    }


   public function DetailsTransfer($id){
    $transfer = Transfer::with(['transferItems.product'])->findOrFail($id);
    $product = Product::find($transfer->product_id);
    $fromWarehouse = WareHouse::find($transfer->from_warehouse_id);
    $toWarehouse = WareHouse::find($transfer->to_warehouse_id);
    return view('admin.backend.transfer.details_transfer',compact('transfer','product','fromWarehouse','toWarehouse'));

   }





}
