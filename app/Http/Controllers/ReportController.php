<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\Sale;
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
                    'id' => $product->id,
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

    public function StockReportView($productId)
    {
        $stockData = PurchaseProduct::with('purchase.dealer')
            ->where('product_id', $productId)->whereNull('status')->whereNull('invoice_id')
            ->get();

        return view('reports.stockShow', compact('stockData'));
    }
    public function imeiReport()
    {
        $products = Product::with(['brand', 'purchaseProducts'])->get();

        $flatProducts = collect();
        foreach ($products as $product) {
            foreach ($product->purchaseProducts as $purchaseProduct) {
                $flatProducts->push([
                    'brand_name'   => $product->brand->name ?? 'No Brand',
                    'product_name' => $product->product_name,
                    'imei'         => $purchaseProduct->imei,
                    'color'        => $purchaseProduct->color,
                    'mrp'          => $product->mrp,
                ]);
            }
        }
        return view('reports.imei', compact('products','flatProducts'));
    }

    public function saleReport()
    {
        $sales = Sale::with(['customer', 'saleProducts.brand', 'saleProducts.product', 'saleProducts'])->get();
        $totalAmount = Sale::where('total_amount','>',0)->sum('total_amount');
        return view('reports.sale',compact('sales','totalAmount'));
    }

    public function paymentReport()
    {
        $totals = SaleTransaction::select('payment_mode', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('payment_mode')
            ->get();

        return view('reports.payment', compact('totals'));
    }
}
