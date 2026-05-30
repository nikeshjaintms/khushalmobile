<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Dealer;
use App\Models\Product;
use App\Models\Purchase;
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
        $brands = Brand::all();
        $productSearch = Product::all();
        $products = Product::with(['brand', 'purchaseProducts'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'brand_id' => $product->brand_id,
                    2,
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
        return view('reports.stock', compact('products', 'brands', 'productSearch'));
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
        return view('reports.imei', compact('products', 'flatProducts'));
    }

    public function saleReport()
    {
        $sales = Sale::with(['customer', 'saleProducts.brand', 'saleProducts.product', 'saleProducts'])->get();
        $totalAmount = Sale::where('total_amount', '>', 0)->sum('total_amount');
        return view('reports.sale', compact('sales', 'totalAmount'));
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
        $financeTotals = SaleTransaction::leftJoin('sales', 'sales_transactions.invoice_id', '=', 'sales.id')
            ->where('sales.payment_method', 2)
            ->select('sales.id', 'sales.total_amount')
            ->groupBy('sales.id', 'sales.total_amount') // prevent duplication
            ->get()
            ->sum('total_amount');

        $nonFinanceTotals = SaleTransaction::leftJoin('sales', 'sales_transactions.invoice_id', '=', 'sales.id')
            ->where('sales.payment_method', '!=', 2)
            ->select(
                'sales_transactions.payment_mode',
                DB::raw('SUM(sales_transactions.amount) as amount')
            )
            ->groupBy('sales_transactions.payment_mode')
            ->get();

        $totals = [
            'finance' => $financeTotals,
            'cash' => 0,
            'online' => 0,
        ];

        foreach ($nonFinanceTotals as $tx) {
            if ($tx->payment_mode === '1') {
                $totals['cash'] += $tx->amount;
            } elseif ($tx->payment_mode === '2') {
                $totals['online'] += $tx->amount;
            }
        }

        return view('reports.payment', compact('totals'));
    }

    /**
 * Dealer Outstanding Report
 * Shows payments due to dealers based on purchase orders
 */
    public function dealerOutstandingReport()
    {
        // Get all dealers with their purchase totals and payment status
        $dealers = Dealer::with(['purchases.purchaseProducts' => function($query) {
            $query->whereNull('deleted_at');
        }])->get();
        
        $dealerOutstanding = [];
        
        foreach ($dealers as $dealer) {
            $totalPurchaseAmount = 0;
            $totalPaidAmount = 0;
            
            foreach ($dealer->purchases as $purchase) {
                // Calculate total purchase amount from purchase products
                $purchaseTotal = $purchase->purchaseProducts->sum('product_total');
                $totalPurchaseAmount += $purchaseTotal;
                
                // You can add payment tracking if you have a payment table for purchases
                // For now, we'll consider the total as outstanding since no payment tracking exists
                // If you have a PurchasePayment model, uncomment the lines below
                /*
                $totalPaidAmount += PurchasePayment::where('purchase_id', $purchase->id)->sum('amount');
                */
            }
            
            $outstandingAmount = $totalPurchaseAmount - $totalPaidAmount;
            
            // Only include dealers with outstanding amount > 0
            if ($outstandingAmount > 0) {
                $dealerOutstanding[] = (object)[
                    'dealer_id' => $dealer->id,
                    'dealer_name' => $dealer->name,
                    'dealer_contact' => $dealer->contact ?? 'N/A',
                    'dealer_email' => $dealer->email ?? 'N/A',
                    'total_purchase_amount' => $totalPurchaseAmount,
                    'total_paid_amount' => $totalPaidAmount,
                    'outstanding_amount' => $outstandingAmount,
                    'purchase_count' => $dealer->purchases->count(),
                ];
            }
        }
        
        // Sort by outstanding amount descending
        usort($dealerOutstanding, function($a, $b) {
            return $b->outstanding_amount <=> $a->outstanding_amount;
        });
        
        // Calculate totals
        $totalOutstanding = array_sum(array_column($dealerOutstanding, 'outstanding_amount'));
        $totalPurchaseValue = array_sum(array_column($dealerOutstanding, 'total_purchase_amount'));
        // dd($dealerOutstanding, $totalOutstanding, $totalPurchaseValue);
        
        return view('reports.dealerOutstanding', compact('dealerOutstanding', 'totalOutstanding', 'totalPurchaseValue'));
    }

    /**
     * Get purchases by dealer for modal view
     */
        public function getDealerPurchases(Request $request)
        {
            $dealerId = $request->get('dealer_id');
            
            $purchases = Purchase::where('dealer_id', $dealerId)
                ->orderBy('po_date', 'desc')
                ->get(['id', 'po_no', 'po_date', 'sub_total', 'total_tax_amount', 'total']);
            
            return response()->json([
                'success' => true,
                'purchases' => $purchases
            ]);
        }
}
