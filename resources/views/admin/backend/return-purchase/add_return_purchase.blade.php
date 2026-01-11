@extends('admin.admin_master')
@section('admin')

<div class="content d-flex flex-column flex-column-fluid">
    <div class="d-flex flex-column-fluid">
        <div class="my-4 container-fluid">
            <div class="d-md-flex align-items-center justify-content-between">
                <h3 class="mb-0">Create Return Purchase</h3>
                <div class="my-2 text-end mt-md-0">
                    <a class="btn btn-outline-primary" href="{{ route('all.return.purchase') }}">Back</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('store.return.purchase')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="row">
                                        <div class="mb-3 col-md-4">
                                            <label class="form-label">Date: <span class="text-danger">*</span></label>
                                            <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" class="form-control">
                                            @error('date')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-4">
                                            <div class="form-group w-100">
                                                <label class="form-label" for="formBasic">Warehouse : <span class="text-danger">*</span></label>
                                                <select name="warehouse_id" id="warehouse_id" class="form-control form-select">
                                                    <option value="">Select Warehouse</option>
                                                    @foreach ($warehouses as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <small id="warehouse_error" class="text-danger d-none">Please select the first warehouse.</small>
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-4">
                                            <div class="form-group w-100">
                                                <label class="form-label" for="formBasic">Supplier : <span class="text-danger">*</span></label>
                                                <select name="supplier_id" id="supplier_id" class="form-control form-select">
                                                    <option value="">Select Supplier</option>
                                                    @foreach ($suppliers as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('supplier_id')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
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
                                                        <th>Stock</th>
                                                        <th>Action</th>
                                                        <th>Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
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
                                                                {{-- Bagian finansial disembunyikan agar sama dengan Purchase --}}
                                                                <tr class="d-none">
                                                                    <td class="py-3">Grand Total</td>
                                                                    <td class="py-3" id="grandTotal">Rp 0</td>
                                                                    <input type="hidden" name="grand_total" value="0">
                                                                </tr>
                                                                <tr class="d-none">
                                                                    <td class="py-3">Paid Amount</td>
                                                                    <td class="py-3">
                                                                        <input type="text" name="paid_amount" value="0" class="form-control">
                                                                    </td>
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
                                            <div class="form-group w-100">
                                                <label class="form-label" for="formBasic">Status : <span class="text-danger">*</span></label>
                                                <select name="status" id="status" class="form-control form-select">
                                                    <option value="">Select Status</option>
                                                    <option value="Return" selected>Return</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Ordered">Ordered</option>
                                                </select>
                                                @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label class="form-label">Notes: </label>
                                        <textarea class="form-control" name="note" rows="3" placeholder="Enter Notes"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12">
                                <div class="mt-5 d-flex justify-content-end">
                                    <button class="btn btn-primary me-3" type="submit">Save</button>
                                    <a class="btn btn-secondary" href="{{ route('all.return.purchase') }}">Cancel</a>
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
    var productSearchUrl = "{{ route('purchase.product.search') }}"
</script>

@endsection
