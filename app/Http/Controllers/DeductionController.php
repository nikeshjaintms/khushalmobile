<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deduction;
use App\Models\Finance;
use App\Models\PurchaseProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$deductions = Deduction::leftjoin('customers', 'deductions.customer_id', '=', 'customers.id')
        //    ->select('deductions.*', 'customers.name as customer_name')
        //    ->where('deductions.status', 'paid')
        //    ->get();
        //$finances = Finance::join('customers', 'finances.customer_id', '=', 'customers.id')
        //    ->leftJoin('deductions', 'finances.id', '=', 'deductions.finance_id')
        //    ->select('finances.*', 'customers.name as customer_name', 'customers.phone as phone', 'customers.city as city','deductions.emi_value_paid','deductions.emi_value_paid','deductions.remaining','deductions.total','finances.month_duration','finances.emi_value')
        //    ->distinct('finances.id')
        //    ->get();

        $finances = DB::table('finances')
            ->join('customers', 'finances.customer_id', '=', 'customers.id')
            ->leftJoin('deductions', function ($join) {
                $join->on('finances.id', '=', 'deductions.finance_id')
                    ->where('deductions.status', '=', 'paid');
            })
            ->select(

                'finances.id',
                'finances.invoice_id',
                'finances.customer_id',
                'finances.month_duration',
                'finances.emi_value',
                'finances.downpayment',
                'finances.status',
                'deductions.total',
                'finances.finance_amount',
                'customers.name as customer_name',
                'customers.phone',
                'customers.city',

                DB::raw('COUNT(deductions.id) as paid_emi_count'),
                DB::raw('finances.month_duration - COUNT(deductions.id) as remaining_emi_count')
            )
            ->groupBy(

                'finances.id',
                'finances.invoice_id',
                'finances.customer_id',
                'finances.month_duration',
                'finances.emi_value',
                'finances.downpayment',
                'finances.status',
                'finances.finance_amount',
                'deductions.total',
                'customers.name',
                'customers.phone',
                'customers.city'
            )
            ->get();


        //dd($finances);


        return view('deduction.index', compact( 'finances'));
    }


    public function create()
    {
        $customers = Customer::get();

        $purchase_product = PurchaseProduct::where('status','sold')->get();


        return view('deduction.create', compact('customers','purchase_product'));
    }

    //public function getFinanceDetails(Request $request)
    //{
    //    $query = Finance::leftjoin('customers', 'finances.customer_id', '=', 'customers.id')
    //        ->leftjoin('sales', 'finances.invoice_id', '=', 'sales.id')
    //        ->leftjoin('products', 'finances.product_id', '=', 'products.id')
    //        ->leftjoin('brands', 'products.brand_id', '=', 'brands.id')
    //        ->leftjoin('purchase_product', 'finances.id', '=', 'purchase_product.purchase_id')
    //        ->select('finances.*', 'customers.name as customer_name', 'customers.phone', 'customers.city', 'sales.invoice_no','brands.name as brand_name','products.product_name','sales.invoice_date','purchase_product.imei')
    //        ->where('finances.customer_id', $request->customer_id)
    //        ->get();
    //
    //    //if (!$finance) {
    //    //    return response()->json(['error' => 'Customer not found'], 404);
    //    //}
    //    if ($request->filled('customer_id')) {
    //        $query->where('finances.customer_id', $request->customer_id);
    //    } elseif ($request->filled('phone')) {
    //        $query->where('customers.phone', $request->phone);
    //    } elseif ($request->filled('imei')) {
    //        $query->where('purchase_product.imei', $request->imei);
    //    } else {
    //        return response()->json(['error' => 'No valid filter provided'], 400);
    //    }
    //
    //    $finance = $query->get();
    //
    //    return response()->json([
    //        'finance' => $finance // or whatever structure you have
    //    ]);
    //}
    public function getFinanceDetails(Request $request)
    {

            $query = Finance::leftJoin('customers', 'finances.customer_id', '=', 'customers.id')
                ->leftJoin('sales', 'finances.invoice_id', '=', 'sales.id')
                ->leftJoin('sales_products', 'sales_products.sales_id', '=', 'sales.id')
                ->leftJoin('products', 'finances.product_id', '=', 'products.id')
                ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
                ->leftJoin('purchase_product', 'sales_products.imei_id', '=', 'purchase_product.id')
                ->select(
                    'finances.*',
                    'customers.name as customer_name',
                    'customers.phone',
                    'customers.city',
                    'sales.invoice_no',
                    'sales.invoice_date',
                    'brands.name as brand_name',
                    'products.product_name',
                    'purchase_product.imei',
                    'purchase_product.id as purchase_id'
                );
            if ($request->filled('customer_id')) {
                $query->where('finances.customer_id', $request->customer_id);
            } elseif ($request->filled('phone')) {
                $query->where('customers.phone', $request->phone);
            } elseif($request->filled('purchase_id')) {
                $query->where('purchase_product.id', $request->purchase_id);
            }
            else {
                return response()->json(['error' => 'No valid filter provided'], 400);
            }


        // Only now execute the query
        $finance = $query->get();

            //dd($finance);
        return response()->json(['finance' => $finance]);
    }

    public function getDeductions(Request $request)
    {
        $financeId = $request->finance_id;

        $deductions = Deduction::where('finance_id', $financeId)->where('status', 'paid')->count();

        $dremaining = Deduction::where('finance_id', $financeId)->whereNotNull('remaining')->orderBy('id', 'desc')->first();

        //$dremaining = array_sum($dremaining->pluck('remaining')->toArray());

        $remaining = $dremaining->remaining ?? 0;

        $sum = Deduction::where('finance_id', $financeId)->sum('emi_value_paid');

        return response()->json([
            'deductions' => $deductions,
            'totalemivalue' => $sum,
            'remaining' => $remaining
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $deduction = new Deduction();
        $deduction->finance_id = $request->finance_id;
        $deduction->customer_id = $request->customer_id;
        $deduction->emi_value = $request->emi_value;
        $deduction->emi_value_paid = $request->emi_value_paid;
        $deduction->emi_date = now();
        $deduction->penalty = $request->penalty;
        $deduction->remaining = $request->remaining;
        $deduction->total = $request->total;
        $deduction->payment_mode = $request->payment_mode;
        $deduction->refernce_no = $request->refernce_no;
        $deduction->status = "paid";
        $deduction->save();

        Session::flash('success', 'Deduction Successfully');

        return redirect()->route('admin.deduction.index');
    }

    /**
     * Display the specified resource.
     */

    public function showByCustomer($customerId, $financeId)
    {
        $deductions = Deduction::leftjoin('finances', 'deductions.finance_id', '=', 'finances.id')
            ->leftjoin('sales', 'finances.invoice_id', '=', 'sales.id')
            ->leftjoin('customers', 'deductions.customer_id', '=', 'customers.id')
            ->select('deductions.*', 'finances.downpayment', 'finances.dedication_date', 'finances.month_duration', 'finances.emi_value', 'finances.penalty','customers.name as customer_name', 'customers.phone', 'customers.city', 'sales.invoice_no')
            ->where('deductions.customer_id', $customerId)
            ->where('deductions.finance_id', $financeId)

            ->get();
        return view('deduction.show', compact('deductions'));
    }

    public function payDeduction(Request $request)
    {
        if ($request->filled('id')) {
            // Update existing deduction
            $deduction = Deduction::findOrFail($request->id);
            $deduction->update([
                'customer_id' => $request->customer_id,
                'finance_id' => $request->finance_id,
                'emi_value_paid' => $request->emi_value_paid,
                'payment_mode' => $request->payment_mode,
                'refernce_no' => $request->refernce_no ?? '',
                'penalty' => $request->penalty,
                'remaining' => $request->remaining,
                'total' => $request->total,
                'status' => 'paid',
                //'emi_date' => now(),
            ]);
        } else {
            // Create new deduction
            $deduction = Deduction::create([
                'customer_id' => $request->customer_id,
                'finance_id' => $request->finance_id,
                'emi_value_paid' => $request->emi_value_paid,
                'payment_mode' => $request->payment_mode,
                'refernce_no' => $request->refernce_no ?? '',
                'penalty' => $request->penalty,
                'remaining' => $request->remaining,
                'total' => $request->total,
                'status' => 'paid',
                //'emi_date' => now(),
            ]);
        }

        $finance = Finance::withSum('deductions as total_paid', 'total')->find($deduction->finance_id);
        if ($finance && $finance->total_paid >= $finance->finance_amount && $finance->status !== 'paid') {
            $finance->update(['status' => 'paid']);
            $finance->save();
        }

        return redirect()->route('admin.deduction.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deduction $deduction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deduction $deduction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction, $id)
    {
        $deduction = Deduction::find($id);
        $deduction->delete();

        Session::flash('success', 'Deduction Deleted Successfully');

        return response()->json([
            'status' => 'success',
            'message' => 'Deduction Deleted Successfully',
        ]);
    }
}
