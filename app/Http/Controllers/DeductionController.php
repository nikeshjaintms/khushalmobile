<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deduction;
use App\Models\Finance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deductions = Deduction::get();
        return view('deduction.index', compact('deductions'));
    }


    public function create()
    {
        $customers = Customer::get();
        return view('deduction.create', compact('customers'));
    }

    public function getFinanceDetails(Request $request)
    {
        $finance = Finance::leftjoin('customers', 'finances.customer_id', '=', 'customers.id')
            ->leftjoin('sales', 'finances.invoice_id', '=', 'sales.id')
            ->select('finances.*', 'customers.name as customer_name', 'sales.invoice_no')
            ->where('finances.customer_id', $request->customer_id)
            ->where('status', 'pending')->get();

        if (!$finance) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json([
            'finance' => $finance // or whatever structure you have
        ]);
    }

    public function getDeductions(Request $request)
    {
        $financeId = $request->finance_id;

        $deductions = Deduction::where('finance_id', $financeId)->count();

        $dremaining = Deduction::where('finance_id', $financeId)->orderby('id','desc')->first();

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
        // dd($request->all());

        $deduction = new Deduction();
        $deduction->finance_id = $request->finance_id;
        $deduction->customer_id = $request->customer_id;
        $deduction->emi_value = $request->emi_value;
        $deduction->emi_value_paid = $request->emi_value_paid;
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
    public function show(Deduction $deduction)
    {
        //
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
