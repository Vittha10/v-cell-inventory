@extends('admin.admin_master')
@section('admin')

<div class="content d-flex flex-column flex-column-fluid">
   <div class="d-flex flex-column-fluid">
      <div class="my-4 container-fluid">
         <div class="d-md-flex align-items-center justify-content-between">
            <h3 class="mb-0"> Sales Return Details</h3>
            <div class="my-2 text-end mt-md-0"><a class="btn btn-outline-primary" href="{{ route('all.sale.return') }}">Back</a></div>
         </div>


 <div class="card">
    <div class="card-body">
    <div class="row">


        <div class="mb-4 col-md-4">
            <div class="border-0 shadow-sm card h-100" style="border-radius: 10px; transition: 0.2s">
                <div class="text-center text-white card-header" style="background: linear-gradient(135deg, #17a2b8, #0d6efd); border-radius:10px 10px 0 0;">
                    <h5 class="mb-0 fw-bold">Customer Information</h5>
                </div>
            <div class="p-4 card-body">
                <div class="mb-3 d-flex align-items-center">
                    <strong class="me-2 text-muted">Name:</strong>
                    <span>{{ $sales->customer->name }}</span>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <strong class="me-2 text-muted">Email:</strong>
                    <span>{{ $sales->customer->email }}</span>
                </div>
                <div class="mb-3 d-flex align-items-center">
                    <strong class="me-2 text-muted">Phone:</strong>
                    <span>{{ $sales->customer->phone }}</span>
                </div>
            </div>

            </div>
        </div>




 <div class="mb-4 col-md-4">
    <div class="border-0 shadow-sm card h-100" style="border-radius: 10px; transition: 0.2s">
        <div class="text-center text-white card-header" style="background: linear-gradient(135deg, #17a2b8, #0d6efd); border-radius:10px 10px 0 0;">
            <h5 class="mb-0 fw-bold">Warehouse Information</h5>
        </div>
    <div class="p-4 card-body">
        <div class="mb-3 d-flex align-items-center">
            <strong class="me-2 text-muted">Warehouse:</strong>
            <span>{{ $sales->warehouse->name }}</span>
        </div>

    </div>

    </div>
</div>




  <div class="mb-4 col-md-4">
    <div class="border-0 shadow-sm card h-100" style="border-radius: 10px; transition: 0.2s">
        <div class="text-center text-white card-header" style="background: linear-gradient(135deg, #17a2b8, #0d6efd); border-radius:10px 10px 0 0;">
            <h5 class="mb-0 fw-bold">Sales Information</h5>
        </div>
    <div class="p-4 card-body">
        <div class="mb-3 d-flex align-items-center">
            <strong class="me-2 text-muted">Sales Date:</strong>
            <span>{{ $sales->date }}</span>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <strong class="me-2 text-muted">Status:</strong>
            <span>{{ $sales->status }}</span>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <strong class="me-2 text-muted">Paid Amount:</strong>
            <span>{{ $sales->paid_amount }}</span>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <strong class="me-2 text-muted">Due Amount:</strong>
            <span>{{ $sales->due_amount }}</span>
        </div>
        <div class="mb-3 d-flex align-items-center">
            <strong class="me-2 text-muted">Grand Total:</strong>
            <span>{{ number_format($sales->grand_total, 2)  }}</span>
        </div>
    </div>

    </div>
</div>

<div class="mt-4 row">
    <div class="col-md-12">
        <div class="card">
            <div class="border-0 shadow-sm card h-100" style="border-radius: 10px; transition: 0.2s">
                <div class="text-center text-white card-header" style="background: linear-gradient(135deg, #17a2b8, #0d6efd); border-radius:10px 10px 0 0;">
                    <h5 class="mb-0 fw-bold">Order Summary</h5>
                </div>


    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Net Unit Cost</th>
                    <th>Discount</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
        <tbody>
        @foreach ($sales->saleReturnItems as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->net_unit_cost,2)  }}</td>
                <td>{{ number_format($item->discount,2)  }}</td>
                <td>{{ number_format($item->subtotal,2)  }}</td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>


            </div>
        </div>
    </div>
</div>


    </div>

            </div>
         </div>
      </div>
   </div>
</div>

@endsection
