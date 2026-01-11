@extends('admin.admin_master')
@section('admin')

<div class="content d-flex flex-column flex-column-fluid">
   <div class="d-flex flex-column-fluid">
      <div class="my-4 container-fluid">
         <div class="d-md-flex align-items-center justify-content-between">
            <h3 class="mb-0">Edit Sales return </h3>
            <div class="my-2 text-end mt-md-0"><a class="btn btn-outline-primary" href="{{ route('all.sale.return') }}">Back</a></div>
         </div>


 <div class="card">
    <div class="card-body">
    <form action="{{ route('update.sale.return',$editData->id)}}" method="post" enctype="multipart/form-data">
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
                <label class="form-label" for="formBasic">Customer : <span class="text-danger">*</span></label>
                <select name="customer_id" id="customer_id" class="form-control form-select" >
                   <option value="">Select Customer</option>
                   @foreach ($customers as $item)
                   <option value="{{ $item->id }}" {{ $editData->customer_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
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
              <tr role="row">
                 <th>Product</th>
                 <th>Net Unit Cost</th>
                 <th>Stock</th>
                 <th>Qty</th>
                 <th>Discount</th>
                 <th>Subtotal</th>
                 <th>Action</th>
              </tr>
           </thead>
           <tbody id="productBody">
    @foreach ($editData->saleReturnItems as $item)
    <tr data-id={{ $item->id  }}>

        <td class="gap-2 d-flex align-items-center">
            <input type="text" class="form-control" value="{{ $item->product->code }} - {{ $item->product->name }}" readonly style="max-width: 300px" >
            <button type="button" class="btn btn-primary btn-sm edit-discount-btn"
            data-id="{{ $item->id }}"
            data-name="{{ $item->product->name }}"
            data-cost="{{ $item->net_unit_cost }}"
            data-bs-toggle="modal" data-bs-target="#discountModal" >
            <span class="mdi mdi-book-edit "></span>
            </button>
        </td>

    <td>
        <input type="number" name="products[{{ $item->product->id }}][net_unit_cost]" class="form-control net-cost" value="{{ $item->net_unit_cost }}" style="max-width: 90px;" readonly>

    </td>
    <td>
        <input type="number" name="products[{{ $item->product->id }}][stock]" class="form-control" value="{{ $item->product->product_qty }}" style="max-width: 80px;" readonly>
    </td>

    <td>
        <div class="input-group">
            <button class="btn btn-outline-secondary decrement-qty" type="button">−</button>
            <input type="text" class="text-center form-control qty-input"
                name="products[{{ $item->product->id }}][quantity]" value="{{ $item->quantity }}" min="1" max="{{ $item->stock }}"
                data-cost="{{ $item->net_unit_cost }}" style="max-width: 50px;">
            <button class="btn btn-outline-secondary increment-qty" type="button">+</button>
        </div>
    </td>

    <td>
        <input type="number" class="form-control discount-input"
            name="products[{{ $item->product->id }}][discount]" value="{{ $item->discount }}" style="max-width: 100px;">
    </td>

    <td class="subtotal">{{ number_format($item->subtotal,2) }}</td>
    <input type="hidden" name="products[{{ $item->product->id }}][subtotal]" value="{{ $item->subtotal }}">

    <td><button type="button" class="btn btn-danger btn-sm remove-item" data-id="{{ $item->id }}"><span class="mdi mdi-delete-circle mdi-18px"></span></button></td>

    </tr>

    @endforeach

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
                   <tr>
                      <td class="py-3">Discount</td>
                      <td class="py-3" id="displayDiscount">Rp {{ $editData->discount }}</td>
                   </tr>
                   <tr>
                      <td class="py-3">Shipping</td>
                      <td class="py-3" id="shippingDisplay">Rp {{ $editData->shipping }}</td>
                   </tr>
                   <tr>
                      <td class="py-3 text-primary">Grand Total</td>
                      <td class="py-3 text-primary" id="grandTotal">Rp {{ $editData->grand_total }}</td>
                      <input type="hidden" name="grand_total" value="{{ $editData->grand_total }}">
                   </tr>


                  <tr>
                      <td class="py-3">Paid Amount</td>
                      <td class="py-3" id="paidAmount">
                      <input type="text" name="paid_amount" value="{{ $editData->paid_amount }}" class="form-control">
                      </td>
                   </tr>

                   <tr>
                      <td class="py-3">Full Paid</td>
                      <td class="py-3" id="fullPaid">
                         <input type="text" class="form-control" name="full_paid" id="fullPaidInput" value="{{ $editData->full_paid }}">
                      </td>
                   </tr>
                   <tr>
                      <td class="py-3">Due Amount</td>
                      <td class="py-3" id="dueAmount">Rp {{ $editData->due_amount }}</td>
                      <input type="hidden" name="due_amount">
                   </tr>


                </tbody>
             </table>
          </div>
       </div>
    </div>
 </div>
</div>


      <div class="row">
         <div class="col-md-4">
            <label class="form-label">Discount: </label>
            <input type="number" id="inputDiscount" name="discount" class="form-control" value="{{ $editData->discount }}">
         </div>
         <div class="col-md-4">
            <label class="form-label">Shipping: </label>
            <input type="number" id="inputShipping" name="shipping" class="form-control" value="{{ $editData->shipping }}">
         </div>
         <div class="col-md-4">
            <div class="form-group w-100">
               <label class="form-label" for="formBasic">Status : <span class="text-danger">*</span></label>
               <select name="status" id="status" class="form-control form-select">
                  <option value="">Select Status</option>
                  <option value="Return" {{ $editData->status == 'Return' ? 'selected' : '' }} >Return</option>
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
           <a class="btn btn-secondary" href="{{ route('all.sale.return') }}">Cancel</a>
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
