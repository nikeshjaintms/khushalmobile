<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function generatePDF($id)
    {
        $sale = Sale::with(['saleProducts', 'saleProducts.product.brand', 'saleProducts.product', 'saleProducts.purchaseProduct'])->where('id', $id)->first();
        return Pdf::loadView('sale.invoice', compact('sale'))->stream("invoice_{$sale->id}.pdf");
    }
}
