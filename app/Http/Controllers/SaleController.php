<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Finance;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$sales = Sale::with('products')->get();
        $sales = Sale::with(['customer', 'products.brand', 'products.product', 'products'])->get();

        return view('sale.index', compact('sales'));
    }

    public function getImeis($product_id)
    {
        $imeis = PurchaseProduct::where('product_id', $product_id)
            ->where('status', null) // Optional: filter only available
            ->pluck('imei', 'id'); // Assuming 'imei' is the column for IMEI number

        return response()->json($imeis);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $customers = Customer::all();
        $date = now()->format('Ymd');
        $random = mt_rand(1000, 9999);
        $invoiceNo = 'INV-' . $date . '-' . $random;
        $products = Product::all();
        //$product = Product::all()->pluck('mrp');
        //dd($product);
        // $brandId=1;
        // $brandId = $request->input('brand_id');
        // $products = Product::where('brand_id', $brandId)->with('brand')->get();
        // dd($products);
        $brands = Brand::all();
        return view('sale.create', compact('customers', 'invoiceNo', 'brands', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'customer_id' => 'required',
            'invoice_no' => 'required',
            'invoice_date' => 'required',
            'sub_total' => 'required',
            'tax_type' => 'required',
            //'tax' => 'required',
            'total_tax_amount' => 'required',
            'total_amount' => 'required',
            'payment_method' => 'required',
            //'discount' => 'required|string',
            //'discount_amount' => 'required',
            //'price' => 'required',
            //'brand_name' => 'required',
            //'product_name' => 'required',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            //'products.*.brand_id' => 'required|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0',
            'products.*.discount_amount' => 'nullable|numeric|min:0',
            'products.*.price_subtotal' => 'numeric|min:0',
            'products.*.tax' => 'numeric|min:0',
            'products.*.tax_amount' => 'numeric|min:0',
            'products.*.price_total' => 'numeric|min:0',


             ]);

          DB::beginTransaction();

          try {
        $sale = Sale::create([
            'customer_id' => $request->customer_id,
            'invoice_no' => $request->invoice_no,
            'invoice_date' => $request->invoice_date,
            'sub_total' => $request->sub_total,
            'tax_type' => $request->tax_type,
            'total_tax_amount' => $request->total_tax_amount,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
        ]);

        // dd($request->all());
        if($request->payment_method == '3' )
        {

        $finance = Finance::create([
            'invoice_no' => $request->invoice_no,
            'product_id' => $request->products[0]['product_id'],
            'customer_id' => $request->customer_id,
            'customer_id' => $request->customer_id,
            'price' => $request->sub_total,
            'downpayment' => $request->DownPayment,
            'processing_fee' => $request->Processing,
            'emi_charger' => $request->EMICharge,
            'finance_amount' => is_numeric($request->FinanceAmount) ? $request->FinanceAmount : 0,
            'month_duration' => is_numeric($request->MonthDuration) ? $request->MonthDuration : 0,
            'emi_value' => $request->permonthvalue,
            'penalty' => $request->Penalty,
            'dedication_date' => $request->DeductionDate,
            'finance_year' => $request->financ_year ?? date('Y')
        ]);
    }
        // Insert related products
        foreach ($request->products as $product) {

            $sale->products()->create([
                'product_id' => $product['product_id'],
                'brand_id' => $product['brand_id'],
                'imei_id' => $product['imei_id'],
                'price' => $product['price'],
                'discount' => $product['discount'] ?? 0,
                'discount_amount' => $product['discount_amount'] ?? 0,
                'price_subtotal' => $product['price_subtotal'] ?? 0,
                'tax' => $product['tax'] ?? 0,
                'tax_amount' => $product['tax_amount'] ?? 0,
                'price_total' => $product['price_total'] ?? 0,
            ]);
        }
        DB::commit();

        return redirect()->route('admin.sale.index')->with('success', 'Sale created successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage());
        return redirect()->route('admin.sale.index')->with('error', 'Something went wrong. Please try again.');
    }
        // Sale::create();
       // dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sales, $id)
    {
        $customers = Customer::all();
        $data = Sale::find($id);
        return view('sale.edit', compact('data','customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sales, $id)
    {
        $validatedData = $request->validate([
            'invoice_no' => 'required',
            'invoice_date' => 'required',
            'sub_total' => 'required',
            'tax_type' => 'required',
            'tax' => 'required',
            'tax_amount' => 'required',
            'total_amount' => 'required',
            'payment_method' => 'required',
            'discount' => 'required',
        ]);

        $data = Sale::findOrFail($id);
        $data->update($validatedData);

        return redirect()->route('admin.sale.index')->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sales, $id)
    {
        $data = Sale::findOrFail($id);
        $data->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Sale deleted successfully.',
            ]
        );
    }
}
