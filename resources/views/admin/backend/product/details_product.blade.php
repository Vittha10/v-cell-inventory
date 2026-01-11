@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="m-0 fs-18 fw-semibold"> Product Details</h4>
            </div>

            <div class="text-end">
                <ol class="py-0 m-0 breadcrumb">
                     <a href="{{ route('all.product') }}" class="btn btn-dark">Back</a>
                </ol>
            </div>
        </div>

        <hr>
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="text-center col-md-4">
                        <h5 class="mb-3">Product Images</h5>
                        <div class="flex-wrap d-flex justify-content-center">
                            @forelse ($product->images as $image)
                                <img src="{{ asset($image->image) }}" alt="image" class="mb-2 me-2" width="200" height="200" style="object-fit: cover; border: 1px solid #ddd; border-radius: 5px">
                            @empty
                                <p class="text-danger">No Image Available</p>
                            @endforelse
                        </div>
                    </div>


                    <div class="col-md-8">
                        <h5 class="mb-3">Product Information</h5>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Name:</strong> {{ $product->name }} </li>
                            <li class="list-group-item"><strong>Code:</strong> {{ $product->code }} </li>


                            <li class="list-group-item"><strong>Warehouse:</strong> Pontianak </li>
                            <li class="list-group-item"><strong>Supplier:</strong> V-Cell Center </li>
                            <li class="list-group-item"><strong>Category:</strong> Elektronik </li>
                            <li class="list-group-item"><strong>Brand:</strong> V-Cell </li>


                            <li class="list-group-item"><strong>Price:</strong> Rp {{ number_format($product->price, 0, ',', '.') }} </li>

                            <li class="list-group-item"><strong>Stock Alert:</strong> {{ $product->stock_alert }} </li>
                            <li class="list-group-item"><strong>Product Qty:</strong> {{ $product->product_qty }} </li>
                            <li class="list-group-item"><strong>Product Status:</strong> {{ $product->status }} </li>
                            <li class="list-group-item"><strong>Product Note:</strong> {{ $product->note }} </li>
                            <li class="list-group-item"><strong>Create On:</strong>
                                {{ \Carbon\Carbon::parse($product->created_at)->format('d F Y') }} </li>
                        </ul>
                    </div>
                </div> </div> </div> </div> </div>

@endsection
