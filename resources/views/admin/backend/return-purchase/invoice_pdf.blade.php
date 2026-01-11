<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 20mm;
            background: #fff;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .invoice-header {
            background: #0d6efd;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }
        .invoice-header h5 {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .info-section {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-box {
            width: 33.33%;
            padding: 15px;
            vertical-align: top;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .info-box h5 {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #0d6efd;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 3px 0;
            font-size: 11px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th {
            background: #e9ecef;
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            vertical-align: top;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h5>Purchase Invoice</h5>
        </div>

        <table class="info-section">
            <tr>
                <td class="info-box">
                    <h5>Supplier Info</h5>
                    <p><strong>Name:</strong> {{ $purchase->supplier->name }}</p>
                    <p><strong>Email:</strong> {{ $purchase->supplier->email }}</p>
                    <p><strong>Phone:</strong> {{ $purchase->supplier->phone }}</p>
                </td>
                <td class="info-box">
                    <h5>Warehouse</h5>
                    <p><strong>Location:</strong></p>
                    <p>{{ $purchase->warehouse->name }}</p>
                </td>
                <td class="info-box">
                    <h5>Purchase Info</h5>
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($purchase->date)->format('d M Y') }}</p>
                    <p><strong>Status:</strong>
                        <span style="color: {{ $purchase->status == 'Received' ? 'green' : 'orange' }}">
                            {{ $purchase->status }}
                        </span>
                    </p>
                </td>
            </tr>
        </table>

        <h5 style="font-weight: bold; margin: 10px 0;">Order Summary</h5>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align: center;">#</th>
                    <th>Product Description</th>
                    <th style="width: 20%; text-align: center;">Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->purchaseItems as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>
                        <strong>{{ $item->product->name }}</strong><br>
                        <small style="color: #666;">Code: {{ $item->product->code }}</small>
                    </td>
                    <td class="text-center">
                        {{ $item->quantity }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 30px; font-size: 10px; color: #999; text-align: center;">
            <p>Generated on: {{ date('d-m-Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
