@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Penyesuaian Stok</h4><br>
                        
                        <form method="post" action="{{ route('update.stock.opname') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $stock_opname->id }}">

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Tanggal SO</label>
                                <div class="col-sm-10">
                                    <input name="tanggal_so" class="form-control" type="date" value="{{ $stock_opname->tanggal_so }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Data Produk</label>
                                <div class="col-sm-10">
                                    <select name="product_id" id="product_id" class="form-select select2">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $pro)
                                        <option value="{{ $pro->id }}" {{ $pro->id == $stock_opname->product_id ? 'selected' : '' }} data-stok="{{ $pro->product_qty }}">{{ $pro->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Stok Sistem</label>
                                <div class="col-sm-10">
                                    <input name="stok_sistem" id="stok_sistem" class="form-control" type="number" value="{{ $stock_opname->stok_sistem }}" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-success">Qty Tambah (+)</label>
                                <div class="col-sm-10">
                                    <input name="qty_tambah" id="qty_tambah" class="form-control" type="number" value="{{ $stock_opname->qty_tambah }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label text-danger">Qty Kurang (-)</label>
                                <div class="col-sm-10">
                                    <input name="qty_kurang" id="qty_kurang" class="form-control" type="number" value="{{ $stock_opname->qty_kurang }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Total Stok Fisik Akhir</label>
                                <div class="col-sm-10">
                                    <input id="total_stok" class="form-control" type="number" value="{{ $stock_opname->stok_fisik }}" readonly style="background-color: #f0f0f0;">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Alasan</label>
                                <div class="col-sm-10">
                                    <textarea name="alasan" class="form-control" rows="3">{{ $stock_opname->alasan }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-2 col-form-label">Status SO</label>
                                <div class="col-sm-10">
                                    <select name="status" class="form-select">
                                        <option value="Requested" {{ $stock_opname->status == 'Requested' ? 'selected' : '' }}>Requested</option>
                                        <option value="Approved" {{ $stock_opname->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    </select>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-info waves-effect waves-light" value="Simpan Perubahan">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Hitung otomatis saat angka berubah
        $('#qty_tambah, #qty_kurang').on('input', function(){
            let sistem = parseFloat($('#stok_sistem').val()) || 0;
            let tambah = parseFloat($('#qty_tambah').val()) || 0;
            let kurang = parseFloat($('#qty_kurang').val()) || 0;
            $('#total_stok').val(sistem + tambah - kurang);
        });

        // Update stok sistem saat produk diganti
        $('#product_id').change(function(){
            let selected = $(this).find('option:selected');
            let stok = selected.data('stok');
            $('#stok_sistem').val(stok);
            $('#qty_tambah').trigger('input');
        });
    });
</script>
@endsection