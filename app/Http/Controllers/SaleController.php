<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Finance;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\SaleTransaction;
use App\Models\Transction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['customer', 'saleProducts.brand', 'saleProducts.product', 'saleProducts'])->get();
        return view('sale.index', compact('sales'));
    }


    public function getImeis($product_id)
    {
        $imeis = PurchaseProduct::where('product_id', $product_id)
            ->where('status', null)
            ->where('invoice_id', null) // Optional: filter only available
            ->pluck('imei', 'id'); // Assuming 'imei' is the column for IMEI number

        return response()->json($imeis);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $customers = Customer::all();
        $brands = Brand::all();
        $products = Product::all();

        // Determine the current fiscal year
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $previousYear = $currentYear - 1;

         // Determine if the fiscal year should be previous year - current year or current year - next year
         if (date('m') < 4) {
            // Before April 1st, use the previous fiscal year
            $fiscalYear = ($previousYear % 100) . '-' . ($currentYear % 100);
        } else {
            // From April 1st onwards, use the current fiscal year
            $fiscalYear = ($currentYear % 100) . '-' . ($nextYear % 100);
        }
        $latestInvoice = Sale::where('invoice_no', 'LIKE', "%/$fiscalYear/%")->latest()->first();

          // Extract and increment the last number, reset if new fiscal year
          if ($latestInvoice) {
            $lastNumber = intval(explode('/', $latestInvoice->invoice_no)[2]);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Reset if no invoice exists for this fiscal year
        }

        $invoiceNo = 'INV/' . $fiscalYear . '/' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);;
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
            'total_tax_amount' => 'required',
            'total_amount' => 'required',
            'payment_method' => 'required',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
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
            if ($request->payment_method == '3') {

                $finance = Finance::create([
                    'invoice_no' => $request->invoice_no,
                    'product_id' => $request->products[0]['product_id'],
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
                $sale->saleProducts()->create([
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

            PurchaseProduct::where('product_id',$product['product_id'])->where('id',$product['imei_id'])
            ->update([
                'status' => 'sold',
                'invoice_id' => $sale->id,
            ]);

            }

            foreach($request->payment as $pay){

                Transction::create([
                    'invoice_id' => $sale->id,
                    'payment_mode' => $pay['payment_mode'],
                    'type' => 'in',
                    'amount' => $pay['amount'],
                    'reference_no' => $pay['reference_no'] ?? NULL,
                    'remark' => $pay['remark'] ?? NULL,
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

        $data = Sale::find($id);
        $data1 = SaleProduct::with('product')->where('sales_id',$id)->first();

        $customers = Customer::all();
        $selectedCustomer = $data->customer->pluck('id');

        $products = Product::all();
        $selectedProductId = $data1->product_id;
        //dd($selectedProductId);

        $brands = Brand::all();
        $selectedBrand  = $data1->product->brand_id;

        $imiNumbers= PurchaseProduct::all();
        $selectedImi = $data1->imei;
        dd($selectedImi);

        //dd($selectedBrand);

        //$sales_transactions = SaleTransaction::all();
        ////$reference_no = $sales_transactions->reference_no;
        //dd($sales_transactions);

        return view('sale.edit', compact('data', 'data1', 'customers', 'selectedCustomer', 'products','brands','selectedProductId','selectedBrand'));
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
