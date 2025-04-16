<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use mysql_xdevapi\CollectionModify;

class InvoiceController extends Controller
{
    public function generatePDF($id)
    {
        $sale = Sale::with(['customer','saleProducts', 'saleProducts.product.brand', 'saleProducts.product', 'saleProducts.purchaseProduct'])->where('id', $id)->first();
        $pdf = Pdf::loadView('sale.invoice', compact('sale'));
        return $pdf->stream("invoice_{$sale->id}.pdf");

    }
}
