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


    public function getProductsByBrand($brandId)
    {
        $products = Product::where('brand_id', $brandId)->get(['id', 'product_name']);
        return response()->json($products);
    }

    public function stockReport()
    {
        $brands= Brand::all();
        $productSearch= Product::all();
        $products = Product::with(['brand', 'purchaseProducts'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'brand_id' => $product->brand_id,2,
                    'brand_name' => $product->brand->name ?? 'No Brand',
                    'product_name' => $product->product_name,
                    'null_status_and_invoice' => $product->purchaseProducts
                        ->filter(function ($purchase) {
                            return is_null($purchase->invoice_id) &&
                                (is_null($purchase->status) || $purchase->status === 'return');
                        })
                        ->count(),
                ];
            });
        return view('reports.stock', compact('products','brands','productSearch'));
    }

    public function StockReportView($productId)
    {
        $stockData = PurchaseProduct::with('purchase.dealer')
            ->where('product_id', $productId)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 'return');
            })
            ->whereNull('invoice_id')
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

    // public function paymentReport()
    // {
    //     $totals = SaleTransaction::select('payment_mode', DB::raw('SUM(amount) as total_amount'))
    //         ->groupBy('payment_mode')
    //         ->get();

    //     return view('reports.payment', compact('totals'));
    // }


     public function paymentReport()
{
    $transactions = SaleTransaction::leftjoin('sales', 'sales_transactions.invoice_id', '=', 'sales.id')
        ->select('sales_transactions.payment_mode', 'sales_transactions.amount', 'sales.payment_method','sales.total_amount')
        ->get();

    $totals = [
        'finance' => 0,
        'cash' => 0,
        'online' => 0,
    ];

    foreach ($transactions as $tx) {
        if ($tx->payment_method == 2) {
            // Finance sale: all its transactions are counted as Finance
            $totals['finance'] += $tx->total_amount;
        } else {
            // Non-finance sale: determine by payment_mode
            $mode = strtolower($tx->payment_mode);
            if ($mode === '1') {
                $totals['cash'] += $tx->amount;
            } elseif ($mode === '2') {
                $totals['online'] += $tx->amount;
            }
        }
    }

    return view('reports.payment', compact('totals'));
}
}
