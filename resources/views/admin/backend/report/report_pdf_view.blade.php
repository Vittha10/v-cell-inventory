<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; padding: 0; font-size: 20px; }
        .header h2 { margin: 5px 0; font-size: 16px; color: #555; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 8px; word-wrap: break-word; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: center; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer-total { background-color: #eee; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>V-CELL INVENTORY</h1>
        <h2>{{ $title }}</h2>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            @if($category == 'stock')
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Product Name</th>
                    <th width="15%">Warehouse</th>
                    <th width="15%">Category</th>
                    <th width="10%">Qty</th>
                    <th width="15%">Unit Price</th>
                    <th width="15%">Grand Total</th>
                </tr>
            @else
                <tr>
                    <th width="12%">Date</th>
                    <th width="18%">Supplier/Customer</th>
                    <th width="20%">Product</th>
                    <th width="10%">Qty</th>
                    <th width="20%">Unit Price</th>
                    <th width="20%">Grand Total</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @php $totalSeluruhnya = 0; @endphp

            @foreach($reports as $report)
                @if($category == 'stock')
                    @php
                        // 1. QTY (Sudah Berhasil)
                        $qty = $report->product_qty ?? 0;

                        // 2. HARGA (Cek beberapa kemungkinan nama kolom di database)
                        $unitPrice = $report->selling_price ?? $report->price ?? $report->buying_price ?? 0;

                        $subTotal = $qty * $unitPrice;
                        $totalSeluruhnya += $subTotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $report->name }}</td>
                        <td class="text-center">{{ $report->warehouse->name ?? '-' }}</td>

                        <td class="text-center">
                            {{ $report->category->category_name ?? $report->category->name ?? '-' }}
                        </td>

                        <td class="text-center">{{ $qty }}</td>
                        <td class="text-right">{{ number_format($unitPrice, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($subTotal, 0, ',', '.') }}</td>
                    </tr>
                @else
                    @php
                        $items = [];
                        if($category == 'purchase' || $category == 'purchase_return') $items = $report->purchaseItems;
                        elseif($category == 'sale') $items = $report->saleItems;
                        elseif($category == 'sale_return') $items = $report->saleReturnItems;
                    @endphp

                    @foreach($items as $item)
                        @php
                            $unitPrice = $item->net_unit_cost ?? ($item->unit_price ?? 0);
                            $qty = $item->quantity ?? 0;
                            $subTotal = $qty * $unitPrice;
                            $totalSeluruhnya += $subTotal;
                        @endphp
                        <tr>
                            <td class="text-center">{{ date('d-m-Y', strtotime($report->date)) }}</td>
                            <td>{{ $report->supplier->name ?? ($report->customer->name ?? '-') }}</td>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-right">{{ number_format($unitPrice, 0, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($subTotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="footer-total">
                <td colspan="{{ $category == 'stock' ? '6' : '5' }}" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right">{{ number_format($totalSeluruhnya, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
