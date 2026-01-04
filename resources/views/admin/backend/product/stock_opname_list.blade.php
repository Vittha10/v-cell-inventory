@extends('admin.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('add.stock.opname') }}" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;"><i class="fas fa-plus-circle"></i> Tambah Stock Opname</a> <br> <br>               
                        <h4 class="card-title">Riwayat Penyesuaian Stok</h4>
                        <table id="datatable" class="table table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal SO</th>
                                <th>Nama Produk</th>
                                <th>Stok Awal</th>
                                <th>(+) / (-)</th>
                                <th>Stok Akhir</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($stock_history as $key => $item)
                                <tr>
                                    <td> {{ $key+1 }} </td>
                                    <td> {{ $item->tanggal_so }} </td>
                                    <td> {{ $item->product_name }} </td>
                                    <td> {{ $item->stok_sistem }} </td>
                                    <td> 
                                        <span class="text-success">+{{ $item->qty_tambah }}</span> / 
                                        <span class="text-danger">-{{ $item->qty_kurang }}</span> 
                                    </td>
                                    <td> <b>{{ $item->stok_fisik }}</b> </td>
                                    <td> 
                                        <span class="badge {{ $item->status == 'Approved' ? 'bg-success' : ($item->status == 'Requested' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
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
@endsection