<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\SaleTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function stockReport()
    {
        $products = Product::with(['brand', 'purchaseProducts'])
            ->get()
            ->map(function ($product) {
                return [
                    'brand_name' => $product->brand->name ?? 'No Brand',
                    'product_name' => $product->product_name,
                    'null_status_and_invoice' => $product->purchaseProducts
                        ->whereNull('status')
                        ->whereNull('invoice_id')
                        ->count(),
                ];
            });
        return view('reports.stock', compact('products'));
    }

    public function imeiReport()
    {

        return view('reports.imei');
    }

    public function saleReport()
    {
        return view('reports.sale');
    }

    public function paymentReport()
    {
        $totals = SaleTransaction::select('payment_mode', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('payment_mode')
            ->get();

        return view('reports.payment', compact('totals'));
    }

}
