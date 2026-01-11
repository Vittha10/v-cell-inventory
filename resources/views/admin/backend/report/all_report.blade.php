@extends('admin.admin_master')
@section('admin')

<div class="m-2 page-content">
    <div class="container">
        @include('admin.backend.report.body.report_top')

    </div>

     {{-- /// end Container  --}}


     <div class="card">

        <nav class="navbar navbar-expand-lg bg-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNav">

 @include('admin.backend.report.body.report_menu')


</div>

    @include('admin.backend.report.body.report_filter')
{{-- /// Date rang filter --}}
{{--
<div class="row">
    <div class="col-md-12 d-flex align-items-center position-relative">
        <select id="date-range" class="form-control large-select">
            <option value="" selected disabled>Select Date Range</option>
            <option value="today">Today</option>
            <option value="this_week">This Week</option>
            <option value="last_week">Last Week</option>
            <option value="this_month">This Month</option>
            <option value="last_month">Last Month</option>
            <option value="custom">Custom Range</option>
        </select>

        <button id="btn-download" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Download
        </button>


    </div>
    --}}

    {{-- // Custom date field  --}}
    <div class="p-3 bg-white shadow dropdown-menu custom-dropdown position-absolute">
        <label for="custom-start-date">Start Date:</label>
        <input type="date" id="custom-start-date" class="mb-2 form-control">

        <label for="custom-end-date">End Date:</label>
        <input type="date" id="custom-end-date" class="mb-2 form-control">

        <button id="apply-filter" class="btn btn-primary w-100">Apply</button>
    </div>
</div>
{{-- /// End Date rang filter  --}}


            </div>
        </nav>

    <div class="card-body">
        <div class="table-responsive">
            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
    <div class="row">
        <div class="col-sm-12">
            <table id="example" class="table table-striped table-bordered dataTable" style="width: 100%;" role="grid" aria-describedby="example_info">
                <thead>
                    <tr role="row">
                        <th>ID</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Warehouse</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unti Price</th>
                        <th>Status</th>
                        <th>Grand Total</th>
                    </tr>
                </thead>
            <tbody>
            @foreach ($purchases as $key=> $purchase)
            @foreach ($purchase->purchaseItems as $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $purchase->date }}</td>
                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $purchase->warehouse->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->name ?? 'N/A'}}</td>
                    <td>{{ $item->quantity ?? 'N/A'}}</td>
                    <td>{{ $item->net_unit_cost ?? 'N/A'}}</td>
                    <td>{{ $purchase->status ?? 'N/A' }}</td>
                    <td>{{ $purchase->grand_total ?? 'N/A' }}</td>
                </tr>
                @endforeach
                @endforeach
            </tbody>

            </table>

        </div>

    </div>

</div>

        </div>
    </div>





     </div>
     {{-- /// End Card --}}

</div>

<style>
    /* Ensure the navbar container is a positioning context */
    .navbar .container-fluid {
        position: relative;
    }

    /* Style the filter container */
    .filter-container {
        position: relative;
        display: inline-block;
        width: 200px; /* Adjust width to fit the select */
        margin-left: 10px;
    }

    /* Style the select element */
    .large-select {
        background-color: #343a40; /* Match navbar background */
        color: white;
        border: 1px solid #495057;
        padding: 5px 10px;
        width: 100%;
        appearance: none; /* Remove default dropdown arrow */
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M7 10l5 5 5-5H7z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
    }

    /* Style the filter icon */
    .mdi-filter-menu {
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        pointer-events: none; /* Prevent icon from interfering with select */
    }

    /* Style the custom dropdown */
    .custom-dropdown {
        display: none; /* Initially hidden */
        position: absolute;
        top: 100%; /* Position below the select */
        right: 0; /* Align to the right of the filter container */
        width: 250px; /* Fixed width for consistency */
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000; /* Ensure it appears above other elements */
    }

    /* Ensure the dropdown fits within the navbar on smaller screens */
    @media (max-width: 991px) {
        .filter-container {
            width: 100%;
            margin-top: 10px;
        }

        .custom-dropdown {
            right: auto;
            left: 0;
            width: 100%;
        }
    }
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateRange = document.getElementById("date-range");
    const downloadBtn = document.getElementById('btn-download');
    const applyFilterBtn = document.getElementById("apply-filter");
    const customDropdown = document.querySelector('.custom-dropdown');

    // --- 1. Fungsi Deteksi Halaman (Agar otomatis tahu sedang di tab mana) ---
    function getActiveCategory() {
        let path = window.location.pathname;
        if (path.includes('purchase/return')) return 'purchase_return';
        if (path.includes('sale/return')) return 'sale_return';
        if (path.includes('purchase')) return 'purchase';
        if (path.includes('sale')) return 'sale';
        if (path.includes('stock')) return 'stock';
        return 'purchase';
    }

    // --- 2. Logika Download (Jalan di semua halaman) ---
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            let kategori = getActiveCategory();
            let range = dateRange.value;
            let start = document.getElementById('custom-start-date').value;
            let end = document.getElementById('custom-end-date').value;

            if (!range) {
                alert('Silahkan pilih Date Range terlebih dahulu!');
                return;
            }

            // Memanggil Controller Download
            window.location.href = `/report/download-pdf?category=${kategori}&range=${range}&start=${start}&end=${end}`;
        });
    }

    // --- 3. Logika Filter Tampilan (AJAX Pintar) ---
    if (dateRange) {
        dateRange.addEventListener("change", function () {
            let selectedValue = this.value;
            let today = new Date();
            let startDate, endDate;

            if (selectedValue === "custom") {
                customDropdown.style.display = "block";
                return;
            } else {
                customDropdown.style.display = "none";
            }

            // Hitung Tanggal
            switch (selectedValue) {
                case "today": startDate = endDate = formatDate(today); break;
                case "this_week": startDate = formatDate(getWeekStart(today)); endDate = formatDate(today); break;
                case "this_month": startDate = formatDate(new Date(today.getFullYear(), today.getMonth(), 1)); endDate = formatDate(today); break;
                case "last_month":
                    startDate = formatDate(new Date(today.getFullYear(), today.getMonth() - 1, 1));
                    endDate = formatDate(new Date(today.getFullYear(), today.getMonth(), 0));
                    break;
            }

            if(startDate && endDate) fetchFilteredData(startDate, endDate);
        });
    }

    if (applyFilterBtn) {
        applyFilterBtn.addEventListener("click", function () {
            let start = document.getElementById("custom-start-date").value;
            let end = document.getElementById("custom-end-date").value;
            if (start && end) fetchFilteredData(start, end);
        });
    }

    // Fungsi Fetch yang bisa mendeteksi URL otomatis
    function fetchFilteredData(startDate, endDate) {
        let kategori = getActiveCategory();
        // Jika kategori ada kata 'sale', pakai route sale, jika tidak pakai purchase
        let fetchUrl = (kategori.includes('sale')) ? '/filter-sales' : '/filter-purchases';

        fetch(`${fetchUrl}?start_date=${startDate}&end_date=${endDate}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            // Update tabel dengan data yang datang (bisa data.purchases atau data.sales)
            updateTable(data.purchases || data.sales || data.reports);
        })
        .catch(error => console.error('Error:', error));
    }

    // --- 4. Fungsi Pembantu ---
    function formatDate(date) { return date.toISOString().split("T")[0]; }
    function getWeekStart(date) {
        let d = new Date(date);
        d.setDate(d.getDate() - d.getDay());
        return d;
    }

    function updateTable(items) {
        let tbody = document.querySelector("#example tbody");
        if(!tbody) return;
        tbody.innerHTML = "";
        // Logika pengisian tabel disesuaikan dengan kebutuhan UI kamu
    }
});
</script>

@endsection
