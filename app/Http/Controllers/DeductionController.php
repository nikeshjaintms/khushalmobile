<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Deduction;
use App\Models\Finance;
use Illuminate\Http\Request;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
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
        ->where('finances.customer_id', $request->customer_id)->get();

        if (!$finance) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json([
            'finance' => $finance // or whatever structure you have
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function destroy(Deduction $deduction)
    {
        //
    }
}
