@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tambah Stock Opname Baru</h4><br>
                        
                        <form method="post" action="{{ route('store.stock.opname') }}">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tanggal SO</label>
                                <div class="col-sm-10">
                                    <input name="tanggal_so" class="form-control" type="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Data Produk</label>
                                <div class="col-sm-10">
                                    <select name="product_id" id="product_id" class="form-select select2" onchange="getOriginalStock()" required>
                                        <option selected="" value="">-- Pilih Produk --</option>
                                        @foreach($products as $item)
                                        <option value="{{ $item->id }}" data-stock="{{ $item->product_qty }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Stok Sistem</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="number" id="stok_sistem" readonly style="background-color: #f4f4f4;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-success">Qty Tambah (+)</label>
                                <div class="col-sm-10">
                                    <input name="qty_tambah" class="form-control" type="number" id="qty_tambah" value="0" oninput="calculateTotal()">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-danger">Qty Kurang (-)</label>
                                <div class="col-sm-10">
                                    <input name="qty_kurang" class="form-control" type="number" id="qty_kurang" value="0" oninput="calculateTotal()">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Total Stok Fisik Akhir</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="number" id="stok_fisik" readonly style="font-weight: bold; color: blue;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Alasan</label>
                                <div class="col-sm-10">
                                    <textarea name="alasan" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Status SO</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select" required>
                                        <option value="Requested">Requested</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Cancel">Cancel</option>
                                    </select>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-primary waves-effect waves-light" value="Simpan Penyesuaian">
                            <a href="{{ route('stock.opname') }}" class="btn btn-secondary waves-effect">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function getOriginalStock() {
        var select = document.getElementById("product_id");
        var stock = select.options[select.selectedIndex].getAttribute('data-stock');
        document.getElementById("stok_sistem").value = stock || 0;
        calculateTotal();
    }
    function calculateTotal() {
        var sistem = parseInt(document.getElementById("stok_sistem").value) || 0;
        var tambah = parseInt(document.getElementById("qty_tambah").value) || 0;
        var kurang = parseInt(document.getElementById("qty_kurang").value) || 0;
        document.getElementById("stok_fisik").value = sistem + tambah - kurang;
    }
</script>
@endsection