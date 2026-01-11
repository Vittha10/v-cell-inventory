@extends('admin.admin_master')
@section('admin')

<div class="m-2 page-content">
    <div class="container">
        @include('admin.backend.report.body.report_top')
    </div>


     <div class="card">

        <nav class="navbar navbar-expand-lg bg-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarNav">

      @include('admin.backend.report.body.report_menu')
</div>

@include('admin.backend.report.body.report_filter')


    <div class="p-3 bg-white shadow dropdown-menu custom-dropdown position-absolute">
        <label for="custom-start-date">Start Date:</label>
        <input type="date" id="custom-start-date" class="mb-2 form-control">

        <label for="custom-end-date">End Date:</label>
        <input type="date" id="custom-end-date" class="mb-2 form-control">

        <button id="apply-filter" class="btn btn-primary w-100">Apply</button>
    </div>
</div>



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
                        <th>Customer</th>
                        <th>Warehouse</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unti Price</th>
                        <th>Status</th>
                        <th>Grand Total</th>
                    </tr>
                </thead>
            <tbody>
            @foreach ($saleReports as $key=> $report)
            @foreach ($report->saleItems as $item)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $report->date }}</td>
                    <td>{{ $report->customer->name ?? 'N/A' }}</td>
                    <td>{{ $report->warehouse->name ?? 'N/A' }}</td>
                    <td>{{ $item->product->name ?? 'N/A'}}</td>
                    <td>{{ $item->quantity ?? 'N/A'}}</td>
                    <td>{{ $item->net_unit_cost ?? 'N/A'}}</td>
                    <td>{{ $report->status ?? 'N/A' }}</td>
                    <td>{{ $report->grand_total ?? 'N/A' }}</td>
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

</div>

<style>

    .navbar .container-fluid {
        position: relative;
    }


    .filter-container {
        position: relative;
        display: inline-block;
        width: 200px;
        margin-left: 10px;
    }


    .large-select {
        background-color: #343a40;
        color: white;
        border: 1px solid #495057;
        padding: 5px 10px;
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M7 10l5 5 5-5H7z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 12px;
    }


    .mdi-filter-menu {
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        pointer-events: none;
    }


    .custom-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        width: 250px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1000;
    }


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
    document.getElementById("date-range").addEventListener("change", function () {
        let selectedValue = this.value;
        let today = new Date();
        let startDate, endDate;

        if (selectedValue === "custom") {
            document.querySelector('.custom-dropdown').style.display = "block";
            return;
        } else {
            document.querySelector('.custom-dropdown').style.display = "none";
        }

        switch (selectedValue) {
            case "today":
                startDate = formatDate(today);
                endDate = formatDate(today);
                break;
            case "this_week":
                startDate = formatDate(getWeekStart(today));
                endDate = formatDate(today);
                break;
            case "last_week":
                let lastWeekStart = new Date(getWeekStart(today));
                lastWeekStart.setDate(lastWeekStart.getDate() - 7);
                let lastWeekEnd = new Date(lastWeekStart);
                lastWeekEnd.setDate(lastWeekStart.getDate() + 6);
                startDate = formatDate(lastWeekStart);
                endDate = formatDate(lastWeekEnd);
                break;
            case "this_month":
                startDate = formatDate(new Date(today.getFullYear(), today.getMonth(), 1));
                endDate = formatDate(today);
                break;
            case "last_month":
                let lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                let lastMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
                startDate = formatDate(lastMonthStart);
                endDate = formatDate(lastMonthEnd);
                break;
            default:
                return;
        }


        fetchFilteredData(startDate, endDate);
    });

    document.getElementById("apply-filter").addEventListener("click", function () {
        let startDate = document.getElementById("custom-start-date").value;
        let endDate = document.getElementById("custom-end-date").value;

        if (startDate && endDate) {
            fetchFilteredData(startDate, endDate);
        } else {
            alert("Please select both start and end dates.");
        }
    });

    function fetchFilteredData(startDate, endDate) {
        fetch(`/filter-sales?start_date=${startDate}&end_date=${endDate}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            updateTable(data.sales);
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    function updateTable(sales) {
        let tbody = document.querySelector("#example tbody");
        tbody.innerHTML = "";

        sales.forEach(sale => {
            sale.sale_items.forEach(item => {

                const netUnitCost = item.net_unit_cost ? parseFloat(item.net_unit_cost) : 0;

                let row = `
                    <tr>
                        <td>${sale.id}</td>
                        <td>${sale.date}</td>
                        <td>${sale.customer ? sale.customer.name : 'N/A'}</td>
                        <td>${sale.warehouse ? sale.warehouse.name : 'N/A'}</td>
                        <td>${item.product ? item.product.name : 'N/A'}</td>
                        <td>${item.quantity}</td>
                        <td>${netUnitCost.toFixed(2)}</td> <!-- Use the validated number -->
                        <td>${sale.status}</td>
                        <td>${sale.grand_total ? parseFloat(sale.grand_total).toFixed(2) : '0.00'}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        });
    }

    function formatDate(date) {
        return date.toISOString().split("T")[0];
    }

    function getWeekStart(date) {
        let d = new Date(date);
        d.setDate(d.getDate() - d.getDay());
        return d;
    }
    </script>

@endsection
