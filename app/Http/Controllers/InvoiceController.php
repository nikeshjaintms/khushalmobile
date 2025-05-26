<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Deduction;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\CollectionModify;

class InvoiceController extends Controller
{
    public function generatePDF($id)
    {
        $sale = Sale::with(['customer','saleProducts', 'saleProducts.product.brand', 'saleProducts.product', 'saleProducts.purchaseProduct'])->where('id', $id)->first();

        $deductions = Deduction::with('finance','customer') ->where('customer_id', $sale->customer_id)->get();

        if($sale->payment_method == '2')
        {
            $pdf = Pdf::loadView('sale.invoice', compact('sale','deductions'));
        }
        else{
            $pdf = Pdf::loadView('sale.invoice', compact('sale'));
        }

        return $pdf->stream("invoice_{$sale->id}.pdf");

    }

    public function generatePdfStock(Request $request)
    {
        $brands = Brand::all();

        $query = Product::with(['brand', 'purchaseProducts']);

        if ($request->filled('brand_id')) {
            $query->where('products.brand_id', $request->brand_id);
        }

        if ($request->filled('product_id')) {
            $query->where('products.id', $request->product_id);
        }

        $products = $query->get();
        $filteredProductIds = $products->pluck('id')->toArray();

        $purchaseProducts = PurchaseProduct::with(['purchase', 'product'])
            ->select('product_id', DB::raw('COUNT(*) as total'))
            ->whereIn('product_id', $filteredProductIds)
            ->whereNull('invoice_id')
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', 'return');
            })
            ->groupBy('product_id')
            ->get();

        $pdf = Pdf::loadView('reports.stockInvoice', compact('brands', 'products','purchaseProducts'));

        return $pdf->stream("filtered_stock.pdf");
    }


}
