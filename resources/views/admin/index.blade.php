@extends('admin.admin_master')
@section('admin')

<div class="content">
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="m-0 fs-18 fw-semibold">Dashboard Rekapan</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="row g-3">

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-1 fs-14 text-muted">Total Penjualan</div>
                                <div class="mb-0 text-black fs-22 fw-bold">
                                    Rp {{ number_format(\App\Models\Sale::sum('grand_total'), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-1 fs-14 text-muted">Total Pembelian</div>
                                <div class="mb-0 text-black fs-22 fw-bold">
                                    Rp {{ number_format(\App\Models\Purchase::sum('grand_total'), 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-1 fs-14 text-muted">Transaksi Penjualan</div>
                                <div class="mb-0 text-black fs-22 fw-bold">
                                    {{ \App\Models\Sale::count() }} Invoice
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-1 fs-14 text-muted">Transaksi Pembelian</div>
                                <div class="mb-0 text-black fs-22 fw-bold">
                                    {{ \App\Models\Purchase::count() }} Invoice
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-3 row">

            <div class="col-md-12 col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="list" class="widgets-icons"></i>
                            </div>
                            <h5 class="mb-0 card-title">Tabel Rekapan Stock Opname</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
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
                <div class="overflow-hidden card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="border border-dark rounded-2 me-2 widget-icons-sections">
                                <i data-feather="shopping-cart" class="widgets-icons"></i>
                            </div>
                            <h5 class="mb-0 card-title">Penjualan Terbaru</h5>
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-traffic">
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
