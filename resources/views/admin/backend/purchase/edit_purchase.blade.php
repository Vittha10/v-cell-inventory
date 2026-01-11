@extends('admin.admin_master')
@section('admin')

<div class="content d-flex flex-column flex-column-fluid">
   <div class="d-flex flex-column-fluid">
      <div class="my-4 container-fluid">
         <div class="d-md-flex align-items-center justify-content-between">
            <h3 class="mb-0">Edit Purchase</h3>
            <div class="my-2 text-end mt-md-0"><a class="btn btn-outline-primary" href="{{ route('all.purchase') }}">Back</a></div>
         </div>


 <div class="card">
    <div class="card-body">
    <form action="{{ route('update.purchase',$editData->id)}}" method="post" enctype="multipart/form-data">
       @csrf


<div class="row">
 <div class="col-xl-12">
    <div class="card">
       <div class="row">
          <div class="mb-3 col-md-4">
             <label class="form-label">Date:  <span class="text-danger">*</span></label>
             <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" class="form-control" value="{{ $editData->date }}">
             @error('date')
             <span class="text-danger">{{ $message }}</span>
             @enderror
          </div>

        <input type="hidden" name="warehouse_id" value="{{ $editData->warehouse_id }}">

          <div class="mb-3 col-md-4">
                <div class="form-group w-100">
                <label class="form-label" for="formBasic">Warehouse : <span class="text-danger">*</span></label>
                <select name="warehouse_id" id="warehouse_id" class="form-control form-select" disabled>
    <option value="">Select Warehouse</option>
    @foreach ($warehouses as $item)
    <option value="{{ $item->id }}" {{ $editData->warehouse_id == $item->id ? 'selected' : '' }} >{{ $item->name }}</option>
    @endforeach
                </select>
                <small id="warehouse_error" class="text-danger d-none">Please select the first warehouse.</small>
                </div>
          </div>

          <div class="mb-3 col-md-4">
             <div class="form-group w-100">
                <label class="form-label" for="formBasic">Supplier : <span class="text-danger">*</span></label>
                <select name="supplier_id" id="supplier_id" class="form-control form-select" >
                   <option value="">Select Supplier</option>
                   @foreach ($suppliers as $item)
                   <option value="{{ $item->id }}" {{ $editData->supplier_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                   @endforeach
                </select>
             </div>
          </div>
       </div>


       <div class="row">
          <div class="mb-3 col-md-12">
             <label class="form-label">Product:</label>
             <div class="input-group">
                   <span class="input-group-text">
                      <i class="fas fa-search"></i>
                   </span>
                   <input type="search" id="product_search" name="search" class="form-control" placeholder="Search product by code or name">
             </div>
             <div id="product_list" class="mt-2 list-group"></div>
          </div>
       </div>




  <div class="row">
     <div class="col-md-12">
        <label class="form-label">Order items: <span class="text-danger">*</span></label>
        <table class="table table-striped table-bordered dataTable" style="width: 100%;">
           <thead>
                <thead>
                    <tr role="row">
                 <th>Product</th>
                 <th>Stock</th>
                 <th>Action</th>
                 <th> Qty </th>
              </tr>
</thead>
            </thead>
           <tbody id="productBody">
    @foreach ($editData->purchaseItems as $item)
    <tr data-id="{{ $item->id }}">
        <td>
            <input type="hidden" name="products[{{ $item->product_id }}][product_id]" value="{{ $item->product_id }}">
            <input type="text" class="form-control" value="{{ $item->product->code }} - {{ $item->product->name }}" readonly>
        </td>
        <td>
            <input type="text" class="form-control" value="{{ $item->product->product_qty }}" readonly>
        </td>
        <td>
            <input type="number" name="products[{{ $item->product_id }}][quantity]" class="form-control" value="{{ $item->quantity }}" min="1">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm remove-item-btn">
                <i class="mdi mdi-delete-circle"></i>
            </button>
        </td>
    </tr>
    @endforeach
</tbody>

           </tbody>
        </table>
     </div>
  </div>

<div class="row">
 <div class="col-md-6 ms-auto">
    <div class="card">
       <div class="pb-2 card-body pt-7">
          <div class="table-responsive">
             <table class="table border">
                <tbody>
                </tbody>
             </table>
          </div>
       </div>
    </div>
 </div>
</div>
         <div class="col-md-4">
            <div class="form-group w-100">
               <label class="form-label" for="formBasic">Status : <span class="text-danger">*</span></label>
               <select name="status" id="status" class="form-control form-select">
                  <option value="">Select Status</option>
                  <option value="Received" {{ $editData->status == 'Received' ? 'selected' : '' }} >Received</option>
                  <option value="Pending"  {{ $editData->status == 'Pending' ? 'selected' : '' }} >Pending</option>
                  <option value="Ordered" {{ $editData->status == 'Ordered' ? 'selected' : '' }} >Ordered</option>
               </select>
               @error('status')
                  <span class="text-danger">{{ $message }}</span>
               @enderror
            </div>
         </div>
      </div>

      <div class="mt-2 col-md-12">
         <label class="form-label">Notes: </label>
         <textarea class="form-control" name="note" rows="3" placeholder="Enter Notes">{{ $editData->note }}</textarea>
      </div>
   </div>
</div>
</div>

     <div class="col-xl-12">
        <div class="mt-5 d-flex justify-content-end">
           <button class="btn btn-primary me-3" type="submit">Save</button>
           <a class="btn btn-secondary" href="{{ route('all.purchase') }}">Cancel</a>
        </div>
     </div>
  </div>
</form>
            </div>
         </div>
      </div>
   </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const productBody = document.getElementById("productBody");


        productBody.addEventListener("input", function (e) {
            if (e.target.classList.contains("qty-input") || e.target.classList.contains("net-cost")) {
                let row = e.target.closest("tr");
                let qty = parseFloat(row.querySelector(".qty-input").value) || 0;
                let cost = parseFloat(row.querySelector(".net-cost").value) || 0;
                let discount = parseFloat(row.querySelector(".discount-input").value) || 0;

                let subtotal = (qty * cost) - discount;
                row.querySelector(".subtotal").textContent = subtotal.toFixed(2);
            }
        });



             document.querySelectorAll(".increment-qty").forEach(button => {
                button.addEventListener("click", function () {
                   let input = this.closest(".input-group").querySelector(".qty-input");
                   let max = parseInt(input.getAttribute("max"));
                   let value = parseInt(input.value);
                   if (value < max) {
                         input.value = value + 1;
                         updateSubtotal(this.closest("tr"));
                   }
                });
             });


             document.querySelectorAll(".decrement-qty").forEach(button => {
                button.addEventListener("click", function () {
                   let input = this.closest(".input-group").querySelector(".qty-input");
                   let min = parseInt(input.getAttribute("min"));
                   let value = parseInt(input.value);
                   if (value > min) {
                         input.value = value - 1;
                         updateSubtotal(this.closest("tr"));
                   }
                });
             });


          function updateSubtotal(row) {
             let qty = parseFloat(row.querySelector(".qty-input").value);
             let discount = parseFloat(row.querySelector(".discount-input").value) || 0;
             let netUnitCost = parseFloat(row.querySelector(".qty-input").dataset.cost);


             let subtotal = (netUnitCost * qty) - discount;


             row.querySelector(".subtotal").innerText = subtotal.toFixed(2);


             row.querySelector("input[name^='products['][name$='][subtotal]']").value = subtotal.toFixed(2);


             updateGrandTotal();
          }




       function updateGrandTotal() {
          let grandTotal = 0;


          document.querySelectorAll(".subtotal").forEach(function (item) {
             grandTotal += parseFloat(item.textContent) || 0;
          });


          let discount = parseFloat(document.getElementById("inputDiscount").value) || 0;
          let shipping = parseFloat(document.getElementById("inputShipping").value) || 0;


          grandTotal = grandTotal - discount + shipping;


          if (grandTotal < 0) {
             grandTotal = 0;
          }


          document.getElementById("grandTotal").textContent = `Rp ${grandTotal.toFixed(2)}`;


          document.getElementById("grandTotalInput").value = grandTotal.toFixed(2);
       }



       productBody.addEventListener("click", function (e) {
            if (e.target.classList.contains("remove-item")) {
                e.target.closest("tr").remove();
                updateGrandTotal();
            }
        });


    });

 </script>


@endsection
