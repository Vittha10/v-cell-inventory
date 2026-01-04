@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Dashboard Rekapan</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="row g-3">
                    
                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="fs-14 mb-1 text-muted">Total Penjualan</div>
                                <div class="fs-22 mb-0 fw-bold text-black">
                                    Rp {{ number_format(\App\Models\Sale::sum('grand_total'), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="fs-14 mb-1 text-muted">Total Pembelian</div>
                                <div class="fs-22 mb-0 fw-bold text-black">
                                    Rp {{ number_format(\App\Models\Purchase::sum('grand_total'), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="fs-14 mb-1 text-muted">Transaksi Penjualan</div>
                                <div class="fs-22 mb-0 fw-bold text-black">
                                    {{ \App\Models\Sale::count() }} Invoice
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="fs-14 mb-1 text-muted">Transaksi Pembelian</div>
                                <div class="fs-22 mb-0 fw-bold text-black">
                                    {{ \App\Models\Purchase::count() }} Invoice
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div> 
        </div> 

        <div class="row mt-3">
            
            <div class="col-md-12 col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="list" class="widgets-icons"></i>
                            </div>
                            <h5 class="card-title mb-0">Tabel Rekapan Stock Opname</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Produk</th>
                                        <th>Sistem</th>
                                        <th>Fisik</th>
                                        <th>Selisih</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Mengambil 5 data SO terbaru. Pastikan relasi 'product' ada di Model StockOpname
                                        $all_so = \App\Models\StockOpname::with('product')->latest()->limit(5)->get();
                                    @endphp
                                    @forelse($all_so as $so)
                                    <tr>
                                        <td>{{ $so->tanggal_so }}</td>
                                        <td>{{ $so->product->name ?? 'N/A' }}</td>
                                        <td>{{ $so->stok_sistem }}</td>
                                        <td>{{ $so->stok_fisik }}</td>
                                        <td class="{{ $so->selisih < 0 ? 'text-danger' : 'text-success' }} fw-bold">
                                            {{ $so->selisih }}
                                        </td>
                                        <td>
                                            <span class="badge {{ $so->status == 'Approved' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $so->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data rekapan.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xl-4">
                <div class="card overflow-hidden">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="shopping-cart" class="widgets-icons"></i>
                            </div>
                            <h5 class="card-title mb-0">Penjualan Terbaru</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-traffic mb-0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $recentSales = \App\Models\Sale::with('customer')->latest()->take(5)->get();
                                    @endphp
                                    @foreach($recentSales as $sale)
                                    <tr>
                                        <td>{{ $sale->customer->name ?? 'Umum' }}</td>
                                        <td>Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $sale->status == 'Sale' ? 'bg-success' : 'bg-warning' }}">
                                                {{ $sale->status }}
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
</div>

@endsection