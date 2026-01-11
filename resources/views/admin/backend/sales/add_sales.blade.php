@extends('admin.admin_master')
@section('admin')

<div class="content d-flex flex-column flex-column-fluid">
    <div class="d-flex flex-column-fluid">
        <div class="my-4 container-fluid">
            <div class="mb-3 d-md-flex align-items-center justify-content-between">
                <h3 class="mb-0">Create Sales</h3>
                <div class="text-end"><a class="btn btn-outline-primary" href="{{ route('all.sale') }}">Back</a></div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('store.sale')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Date: <span class="text-danger">*</span></label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control">
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">No Transaksi: <span class="text-danger">*</span></label>
                                <input type="text" name="invoice_no"
                                       value="SLS-{{ date('Ymd') }}-{{ strtoupper(Str::random(5)) }}"
                                       class="form-control" readonly style="background-color: #f8f9fa; font-weight: bold;">
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Warehouse: <span class="text-danger">*</span></label>
                                <select name="warehouse_id" id="warehouse_id" class="form-control form-select">
                                    <option value="">Select Warehouse</option>
                                    @foreach ($warehouses as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Customer: <span class="text-danger">*</span></label>
                                <select name="customer_id" id="customer_id" class="form-control form-select">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 col-md-12">
    <label class="form-label fw-bold">Product:</label>
    <div class="input-group" style="position: relative;"> <span class="input-group-text"><i class="fas fa-search"></i></span>
        <input type="search" id="product_search" class="form-control" placeholder="Ketik nama laptop..." autocomplete="off">

        <div id="product_list" style="position: absolute; top: 100%; left: 0; width: 100%; z-index: 9999; display: none; background: white; border: 1px solid #ddd;"></div>
    </div>
</div>

                        <div class="row">
                            <div class="mb-4 col-md-12">
                                <label class="form-label fw-bold">Order items: <span class="text-danger">*</span></label>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" style="width: 100%;">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>Product</th>
                                                <th>Net Unit Cost</th>
                                                <th>Stock</th>
                                                <th>Qty</th>
                                                <th>Discount</th>
                                                <th>Subtotal</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Discount:</label>
                                        <input type="number" id="inputDiscount" name="discount" class="form-control" value="0.00">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Shipping:</label>
                                        <input type="number" id="inputShipping" name="shipping" class="form-control" value="0.00">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Status: <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-control form-select">
                                            <option value="">Select Status</option>
                                            <option value="Sale">Sale</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Ordered">Ordered</option>
                                        </select>
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label class="form-label">Notes:</label>
                                        <textarea class="form-control" name="note" rows="3" placeholder="Enter Notes"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="border shadow-sm card">
                                    <div class="p-0 card-body">
                                        <table class="table mb-0 table-borderless">
                                            <tr class="border-bottom">
                                                <td class="p-3">Discount</td>
                                                <td class="p-3 text-end" id="displayDiscount">Rp 0</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="p-3">Shipping</td>
                                                <td class="p-3 text-end" id="shippingDisplay">Rp 0</td>
                                            </tr>
                                            <tr class="border-bottom bg-light">
                                                <td class="p-3 text-primary fw-bold">Grand Total</td>
                                                <td class="p-3 text-primary text-end fw-bold" id="grandTotal">Rp 0</td>
                                                <input type="hidden" name="grand_total">
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="p-3">Paid Amount</td>
                                                <td class="p-3">
                                                    <input type="text" name="paid_amount" placeholder="Enter amount paid" class="form-control text-end">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-3">Due Amount</td>
                                                <td class="p-3 text-danger text-end fw-bold" id="dueAmount">Rp 0</td>
                                                <input type="hidden" name="due_amount">
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 row">
                            <div class="col-md-12 text-end">
                                <hr>
                                <button class="btn btn-primary btn-lg me-2" type="submit">Save Sale</button>
                                <a class="btn btn-secondary btn-lg" href="{{ route('all.sale') }}">Cancel</a>
                            </div>
                        </div>

                    </form>
                </div> </div> </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var productSearchUrl = "{{ route('purchase.product.search') }}";

        $('#product_search').on('keyup', function() {
            var query = $(this).val();
            if (query.length >= 2) {
                $.ajax({
                    url: productSearchUrl,
                    type: 'GET',
                    data: { search: query },
                    success: function(data) {
                        $('#product_list').html(data).show();
                    }
                });
            } else {
                $('#product_list').hide();
            }
        });


        $(document).on('click', function(e) {
            if (!$(e.target).closest('#product_search, #product_list').length) {
                $('#product_list').hide();
            }
        });
    });


    function selectProduct(id, name, price, stock) {
        var row = `<tr>
            <td>${name} <input type="hidden" name="products[${id}][id]" value="${id}"></td>
            <td><input type="number" name="products[${id}][net_unit_cost]" class="form-control" value="${price}"></td>
            <td>${stock}</td>
            <td><input type="number" name="products[${id}][quantity]" class="form-control" value="1"></td>
            <td><input type="number" name="products[${id}][discount]" class="form-control" value="0"></td>
            <td>Rp ${price}</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
        </tr>`;

        $('table tbody').append(row);
        $('#product_list').hide();
        $('#product_search').val('');
    }

    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });
</script>

<style>

    #product_list {
        position: absolute;
        z-index: 9999;
        width: 95%;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 8px 15px rgba(0,0,0,0.2);
    }
    .list-group-item {
        padding: 12px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }
    .list-group-item:hover {
        background-color: #f0f7ff;
        color: #007bff;
    }
</style>

@endsection
