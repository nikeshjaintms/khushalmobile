<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
}
