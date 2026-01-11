<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\WareHouse;
use App\Models\Sale;
use App\Models\SaleReturn;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\ReturnPurchase;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function AllReport(){
        $purchases = Purchase::with(['purchaseItems.product','supplier','warehouse'])->get();
        return view('admin.backend.report.all_report',compact('purchases'));
    }

    public function DownloadPdf(Request $request)
    {
        $category = $request->category;
        $range = $request->range;
        $start_date = $request->start;
        $end_date = $request->end;
        $start = null;
        $end = null;

        if ($range == 'today') {
            $start = Carbon::today()->startOfDay();
            $end = Carbon::today()->endOfDay();
        } elseif ($range == 'this_week') {
            $start = Carbon::now()->startOfWeek();
            $end = Carbon::now()->endOfWeek();
        } elseif ($range == 'this_month') {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        } elseif ($range == 'custom' && $start_date && $end_date) {
            $start = Carbon::parse($start_date)->startOfDay();
            $end = Carbon::parse($end_date)->endOfDay();
        }
        switch ($category) {
            case 'purchase':
                $query = Purchase::with(['purchaseItems.product','supplier','warehouse']);
                $title = "Purchase Report";
                break;
            case 'purchase_return':
                $query = ReturnPurchase::with(['purchaseItems.product','supplier','warehouse']);
                $title = "Purchase Return Report";
                break;
            case 'sale':
                $query = Sale::with(['saleItems.product','customer','warehouse']);
                $title = "Sales Report";
                break;
            case 'sale_return':
                $query = SaleReturn::with(['saleReturnItems.product','customer','warehouse']);
                $title = "Sales Return Report";
                break;
            case 'stock':
                $query = Product::with(['category', 'warehouse'])->latest();
                $title = "Stock Report";
                break;
            default:
                $query = Purchase::with(['purchaseItems.product','supplier','warehouse']);
                $title = "Report";
        }
        if ($start && $end && $category != 'stock') {
            $query->whereBetween('date', [$start, $end]);
        }

        $reports = $query->get();
        $pdf = Pdf::loadView('admin.backend.report.report_pdf_view', compact('reports', 'title', 'category'));

        return $pdf->download('Report_'.$category.'_'.date('Ymd').'.pdf');
    }
    public function FilterPurchases(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query = Purchase::with(['purchaseItems.product','supplier','warehouse']);
        if ($startDate && $endDate ) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('date',[$startDate,$endDate]);
        }
        $purchases = $query->get();
        return response()->json(['purchases' => $purchases]);
    }

    public function PurchaseReturnReport(){
        $returnPurchases = ReturnPurchase::with(['purchaseItems.product','supplier','warehouse'])->get();
        return view('admin.backend.report.purchase_return_report',compact('returnPurchases'));
    }

    public function SaleReport(){
        $saleReports = Sale::with(['saleItems.product','customer','warehouse'])->get();
        return view('admin.backend.report.sale_report',compact('saleReports'));
    }

    public function FilterSales(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query = Sale::with(['saleItems.product','customer','warehouse']);
        if ($startDate && $endDate ) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('date',[$startDate,$endDate]);
        }
        $sales = $query->get();
        return response()->json(['sales' => $sales]);
    }

    public function SaleReturnReport(){
        $returnSales = SaleReturn::with(['saleReturnItems.product','customer','warehouse'])->get();
        return view('admin.backend.report.sales_return_report',compact('returnSales'));
    }

    public function ProductStockReport(){
        $products = Product::with(['category','warehouse'])->get();
        return view('admin.backend.report.stock_report',compact('products'));
    }
}
