<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = Purchase::join('dealers', 'purchases.dealer_id', '=', 'dealers.id')
            ->select("purchases.*", 'dealers.name')
            ->get();

        return view('purchase_products.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dealers = Dealer::get();
        $brands = Brand::get();

        return view('purchase_products.create', compact('dealers', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $add = new Purchase();
            $add->dealer_id = $request->post('dealer_id');
            $add->po_no = $request->post('po_no');
            $add->po_date = $request->post('po_date');
            $add->sub_total = $request->post('sub_total');
            $add->tax_type = $request->post('tax_type');
            $add->total_tax_amount = $request->post('total_tax_amount');
            $add->total = $request->post('total');
            $add->save();

            $prices = $request->post('price');
            $colors = $request->post('color'); // <-- Add this line
            $imeis = $request->post('imei');
            $discounts = $request->post('discount');
            $discountAmounts = $request->post('discount_amount');
            $subtotals = $request->post('price_subtotal');
            $taxes = $request->post('tax');
            $taxAmounts = $request->post('tax_amount');
            $totals = $request->post('product_total');

            // Optional: If you also get product_id or name
            $product_ids = $request->post('product_id'); // if available

            if ($prices && is_array($prices)) {
                foreach ($prices as $index => $price) {
                    $product = new PurchaseProduct();
                    $product->purchase_id = $add->id;
                    $product->product_id = $product_ids[$index] ?? null;
                    $product->color = $colors[$index] ?? null;
                    $product->imei = $imeis[$index] ?? null;
                    $product->price = $price;
                    $product->discount = $discounts[$index] ?? 0;
                    $product->discount_amount = $discountAmounts[$index] ?? 0;
                    $product->price_subtotal = $subtotals[$index] ?? 0;
                    $product->tax = $taxes[$index] ?? 0;
                    $product->tax_amount = $taxAmounts[$index] ?? 0;
                    $product->product_total = $totals[$index] ?? 0;
                    $product->save();
                }
            }
            DB::commit();
            Session::flash('success', "Purchase Order saved! ");

            return redirect()->route('admin.purchase.index');
        } catch (\Exception $e) {
            DB::rollBack();
            // Optionally log the error for debugging
            // dd("Error saving purchase: " . $e->getMessage());

            Session::flash('error', "Something went wrong while saving the purchase order.");
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase, $id)
    {
        $purchases = Purchase::leftjoin('dealers', 'purchases.dealer_id', '=', 'dealers.id')
            ->select("purchases.*", 'dealers.name')
            ->find($id);

        $purchase_products = PurchaseProduct::join('products', 'purchase_product.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->select('purchase_product.*', 'products.product_name', 'brands.name as brand')
            ->where('purchase_id', $id)->get();


        return view('purchase_products.show', compact('purchases', 'purchase_products'));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase, $id)
    {
        $purchase = Purchase::find($id);
        $purchase_products = PurchaseProduct::join('products', 'purchase_product.product_id', '=', 'products.id')
            ->select('purchase_product.*', 'products.brand_id')
            ->where('purchase_id', $id)->get();
        $dealers = Dealer::get();
        $brands = Brand::get();
        $products = Product::get();
        return view('purchase_products.edit', compact('products', 'purchase', 'purchase_products', 'dealers', 'brands'));

        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        // Update purchase main fields
        $purchase->dealer_id = $request->post('dealer_id');
        $purchase->po_no = $request->post('po_no');
        $purchase->po_date = $request->post('po_date');
        $purchase->sub_total = $request->post('sub_total');
        $purchase->tax_type = $request->post('tax_type');
        $purchase->total_tax_amount = $request->post('total_tax_amount');
        $purchase->total = $request->post('total');
        $purchase->save();

        // Gather posted product data
        $product_ids = $request->post('product_id');
        $purchase_product_ids = $request->post('purchase_product_id'); // Hidden input in the form
        $colors = $request->post('color');
        $imeis = $request->post('imei');
        $prices = $request->post('price');
        $discounts = $request->post('discount');
        $discountAmounts = $request->post('discount_amount');
        $subtotals = $request->post('price_subtotal');
        $taxes = $request->post('tax');
        $taxAmounts = $request->post('tax_amount');
        $totals = $request->post('product_total');

        $received_ids = []; // Store all processed PurchaseProduct IDs

        if (is_array($prices)) {
            foreach ($prices as $index => $price) {
                $pp_id = $purchase_product_ids[$index] ?? null;

                $product = $pp_id ? PurchaseProduct::find($pp_id) : new PurchaseProduct();

                if (!$product) {
                    $product = new PurchaseProduct();
                    $product->purchase_id = $purchase->id;
                }

                $product->purchase_id = $purchase->id;
                $product->product_id = $product_ids[$index] ?? null;
                $product->color = $colors[$index] ?? null;
                $product->imei = $imeis[$index] ?? null;
                $product->price = $price ?? 0;
                $product->discount = $discounts[$index] ?? 0;
                $product->discount_amount = $discountAmounts[$index] ?? 0;
                $product->price_subtotal = $subtotals[$index] ?? 0;
                $product->tax = $taxes[$index] ?? 0;
                $product->tax_amount = $taxAmounts[$index] ?? 0;
                $product->product_total = $totals[$index] ?? 0;
                $product->save();

                if ($pp_id) {
                    $received_ids[] = $pp_id;
                } else {
                    $received_ids[] = $product->id;
                }
            }
        }

        // 💥 Delete removed products
        PurchaseProduct::where('purchase_id', $purchase->id)
            ->whereNotIn('id', $received_ids)
            ->delete();

        Session::flash('success', "Purchase Order updated!");
        return redirect()->route('admin.purchase.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase, $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json(['error' => 'Not Found Any Puchase Order'], 400);
        }
        PurchaseProduct::where('purchase_id', $id)->delete();

        $purchase->delete();

        return response()->json(['status' => 'success', 'message' => "Deleted Successfully "], 200);
    }
}
