<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\PurchaseProduct;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    //
    public function index(){
        $purchase_products = PurchaseProduct::join('products', 'purchase_product.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select('purchase_product.*', 'products.product_name', 'brands.name as brand')
            ->where('purchase_product.status', 'return')
            ->get();
        return view('return.index',compact('purchase_products'));
    }

    public function create()
    {
     $brands = Brand::all();
        $products = Product::get();
        return view('return.create',compact('products','brands'));
    }

    public function getReturnDetails(Request $request)
    {
        $brand_id = $request->brand_id;
        $purchase_products = PurchaseProduct::join('products', 'purchase_product.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select('purchase_product.*', 'products.product_name', 'brands.name as brand')
            ->where('brands.id', $brand_id)
            ->where('purchase_product.status', 'sold')
        ->get();

        return response()->json([
            'purchase_products' =>  $purchase_products
        ]);
    }

}
