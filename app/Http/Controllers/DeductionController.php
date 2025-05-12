<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deduction;
use App\Models\Finance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deductions = Deduction::leftjoin('customers', 'deductions.customer_id', '=', 'customers.id')
            ->select('deductions.*', 'customers.name as customer_name')
            ->where('deductions.status', 'paid')
            ->get();
        $finances = Finance::join('customers', 'finances.customer_id', '=', 'customers.id')
            ->select('finances.*', 'customers.name as customer_name')
            ->where('finances.status', 'paid')
            ->get();


        return view('deduction.index', compact('deductions', 'finances'));
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
            ->select('finances.*', 'customers.name as customer_name', 'customers.phone', 'customers.city', 'sales.invoice_no')
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
            ->select('deductions.*', 'finances.downpayment', 'finances.dedication_date', 'finances.month_duration', 'finances.emi_value', 'customers.name as customer_name', 'customers.phone', 'customers.city', 'sales.invoice_no')
            ->where('deductions.customer_id', $customerId)
            ->where('deductions.finance_id', $financeId)
            ->where('finances.status', 'pending')
            ->get();
        foreach ($deductions as $deduction) {
            $deduction->paid_date = $deduction->status === 'paid'
                ? Carbon::now()->format('Y-m-d')
                : null;
        }

        return view('deduction.show', compact('deductions'));
    }

    public function pay(Request $request)
    {
        //dd($request->all());
        $deduction = Deduction::findOrFail($request->id);
        $deduction->update([
            'emi_value_paid' => $request->emi_value_paid,
            'payment_mode' => $request->payment_mode,
            'refernce_no' => $request->refernce_no ?? '',
            'penalty' => $request->penalty,
            'remaining' => $request->remaining,
            'total' => $request->total,
            'status' => 'paid',
            //'emi_date' => now(),
        ]);

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
