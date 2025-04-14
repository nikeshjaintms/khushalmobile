<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getProducts($brand_id)
    {
        $products = Product::where('brand_id', $brand_id)->get();
        return response()->json($products);
    }

    public function getPrice($id)
    {
        $product = Product::find($id);
        return response()->json(['mrp' => $product->mrp]);
    }

    public function index()
    {
        $products = Product::get();

        return view('products.index',compact('products'));
    }

    
    public function create()
    {
        $brands = Brand::get();

        return view('products.create',compact('brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required',
            'product_name' => 'required|min:2',
            'mrp' => 'required'
        ]);

        $add = new Product();
        $add->brand_id = $request->post('brand_id');
        $add->product_name = $request->post('product_name');
        $add->mrp = $request->post('mrp');
        $add->price = $request->post('price') ?? null;
        $add->serial = $request->post('serial') ?? null;
        $add->save();

        Session::flash('success','Product Stored Successfully');

        return redirect()->route('admin.product.index');


    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product, $id)
    {
        $product = Product::where('id',$id)->first();

        $brands = Brand::get();

        return view('products.edit',compact('product','brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, $id)
    {
        // dd($request->all());
        $update = Product::find($id);
        $update->brand_id = $request->brand_id;
        $update->product_name = $request->product_name;
        $update->mrp = $request->mrp;
        $update->save();

        Session::flash('success','Product Updated Successfully');

        return redirect()->route('admin.product.index');


        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product,$id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product Deleted Successfully'
        ]);

    }
}
