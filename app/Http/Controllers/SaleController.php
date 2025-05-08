<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Deduction;
use App\Models\Finance;
use App\Models\financeMaster;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\SaleTransaction;
use App\Models\Transction;
use Carbon\Carbon;
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
        $financeMasters = FinanceMaster::all();
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
        return view('sale.create', compact('customers', 'invoiceNo', 'brands', 'products', 'financeMasters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
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

            if ($request->payment_method == '2') {
                $finance = Finance::create([
                    'invoice_id' => $sale->id,
                    'product_id' => $request->products[0]['product_id'],
                    'customer_id' => $request->customer_id,
                    'mobile_security_charges' => $request->mobile_security_charges,
                    'finances_master_id' => $request->finances_master_id,
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

                PurchaseProduct::where('product_id', $product['product_id'])->where('id', $product['imei_id'])
                    ->update([
                        'status' => 'sold',
                        'invoice_id' => $sale->id,
                    ]);
            }

            foreach ($request->payment as $pay) {

                SaleTransaction::create([
                    'invoice_id' => $sale->id,
                    'payment_mode' => $pay['payment_mode'],
                    //'type' => 'in',
                    'amount' => $pay['amount'],
                    'reference_no' => $pay['reference_no'] ?? null,
                ]);
            }
            
            $startDate = Carbon::createFromDate(now()->year, now()->month, $finance->dedication_date)->addMonth();
            for ($i = 0; $i < $finance->month_duration; $i++) {
                $dueDate = $startDate->copy()->addMonths($i);
                Deduction::create([
                    'customer_id' => $finance->customer_id,
                    'finance_id' => $finance->id,
                    'status' => 'unpaid',
                    'emi_date' =>  $dueDate->format('Y-m-d'),
                    'emi_value' => $request->emi_value,
                    'created_at' => $dueDate,
                    'updated_at' => $dueDate,
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
    public function show(Sale $sales, $id)
    {
        $sale = Sale::with(['customer', 'saleProducts', 'saleProducts.product.brand', 'saleProducts.product', 'saleProducts.purchaseProduct'])->where('id', $id)->first();
        $refernce = SaleTransaction::where('invoice_id', $id)->first();
        $selectedRefer = $refernce->reference_no ?? null;
        $finance = Finance::where('customer_id', $sale->customer_id)->first();

        return view('sale.show', compact('sale', 'selectedRefer', 'refernce', 'finance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Sale $sales, $id)
    {
        $data = Sale::find($id);
        $data1 = SaleProduct::with('product')->where('sales_id', $id)->get();

        $customers = Customer::all();
        $selectedCustomer = $data->customer->pluck('id');

        $products = Product::all();

        foreach ($data1 as $saleProduct) {
            $selectedProductId = $saleProduct->product->id;
            $selectedBrandId = $saleProduct->product->brand_id;
            $selectedImi = $saleProduct->imei_id ?? null;
        }

        $brands = Brand::all();

        $imiNumbers = PurchaseProduct::all();

        $refernce = SaleTransaction::where('invoice_id', $id)->first();
        $selectedRefer = $refernce->reference_no ?? null;
        $selectedAmount = $refernce->amount ?? null;

        $sale = Sale::with(['customer', 'saleProducts', 'saleProducts.product.brand', 'saleProducts.product', 'saleProducts.purchaseProduct'])->where('id', $id)->first();

        $financeMaster = financeMaster::all();
        $selectedFinance = $financeMaster->pluck('id');

        $selectfinance = Finance::where('customer_id', $sale->customer_id)->where('invoice_id', $id)->first();

        return view('sale.edit', compact('data', 'data1', 'customers', 'selectedCustomer', 'products', 'brands', 'selectedProductId', 'selectedBrandId', 'imiNumbers', 'selectfinance', 'selectedImi', 'refernce', 'selectedRefer', 'selectedAmount', 'financeMaster', 'selectedFinance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sales, $id)
    {
        //dd($request->all());
        DB::beginTransaction();
        try {
            $data = Sale::all()->find($id);
            $data->update([
                'customer_id' => $request->customer_id,
                'invoice_no' => $request->invoice_no,
                'invoice_date' => $request->invoice_date,
                'sub_total' => $request->sub_total,
                'tax_type' => $request->tax_type,
                'total_tax_amount' => $request->total_tax_amount,
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->products as $product) {
                $data->saleProducts()->update([
                    'product_id' => $product['product_id'],
                    //'brand_id' => $product['brand_id'],
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

            foreach ($request->payment as $pay) {
                SaleTransaction::create([
                    'invoice_id' => $data->id,
                    'payment_mode' => $pay['payment_mode'],
                    //'type' => 'in',
                    'amount' => $pay['amount'],
                    'reference_no' => $pay['reference_no'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.sale.index')->with('success', 'Sale updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
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
