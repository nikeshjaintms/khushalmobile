<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Customer;
use App\Models\Deduction;
use App\Models\Finance;
use App\Models\FinanceMaster;
use App\Models\Product;
use App\Models\PurchaseProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\SaleTransaction;
use App\Models\Transction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $finances = Finance::with('financeMaster')->get();
        //foreach ($finances as $finance) {
        //     $finance->financeMaster->name;
        //}

        return view('finance.index', compact('finances'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $finances = Finance::all();
        $financeMasters = FinanceMaster::all();
        $customers = Customer::all();
        //$currentYear = date('Y');
        //$nextYear = $currentYear + 1;
        //$previousYear = $currentYear - 1;
        //
        //// Determine if the fiscal year should be previous year - current year or current year - next year
        //if (date('m') < 4) {
        //    // Before April 1st, use the previous fiscal year
        //    $fiscalYear = ($previousYear % 100) . '-' . ($currentYear % 100);
        //} else {
        //    // From April 1st onwards, use the current fiscal year
        //    $fiscalYear = ($currentYear % 100) . '-' . ($nextYear % 100);
        //}
        //$latestInvoice = Sale::where('invoice_no', 'LIKE', "%/$fiscalYear/%")->latest()->first();
        //
        //// Extract and increment the last number, reset if new fiscal year
        //if ($latestInvoice) {
        //    $lastNumber = intval(explode('/', $latestInvoice->invoice_no)[2]);
        //    $nextNumber = $lastNumber + 1;
        //} else {
        //    $nextNumber = 1; // Reset if no invoice exists for this fiscal year
        //}
        //
        //$invoiceNo = 'INV/' . $fiscalYear . '/' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);;
        return view('finance.create', compact( 'financeMasters','finances','customers'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'file_no' => 'required|string',
            'customer_id' => 'required|exists:customers,id',
            'mobile_security_charges' => 'required|numeric',
            'finance_year' => 'required|integer',
            'dedication_date' => 'required|date',
            'penalty' => 'required|numeric',
            'month_duration' => 'required|integer|min:1',
            'emi_value' => 'required|numeric',
            'finance_amount' => 'required|numeric',
            'emi_charger' => 'required|numeric',
            'processing_fee' => 'required|numeric',
            'downpayment' => 'required|numeric',
        ]);

        $finance=Finance::create([
           'ref_mobile_no' => $request->ref_mobile_no,
           'ref_city'=> $request->ref_city,
           'ref_name' => $request->ref_name,
           'file_no' => $request->file_no,
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

        if ($finance) {
            $startDate = Carbon::createFromDate(now()->year, now()->month, $finance->dedication_date)->addMonth();
            for ($i = 0; $i < $finance->month_duration; $i++) {
                $dueDate = $startDate->copy()->addMonths($i);
                Deduction::create([
                    'customer_id' => $finance->customer_id,
                    'finance_id' => $finance->id,
                    'status' => 'Unpaid',
                    'emi_date' => $dueDate->format('Y-m-d'),
                    'emi_value' => $request->permonthvalue,
                    'created_at' => $dueDate,
                    'updated_at' => $dueDate,
                ]);
            }
        }
            return redirect()->route('admin.finance.index')->with('success', 'Finance created successfully.');

    }


    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Finance $finance, $id)
    {
        $finances = Finance::with('financeMaster','customers')->findOrFail($id);
        $financeMaster = FinanceMaster::all();
        $customers = Customer::all();
        return view('finance.edit', compact( 'finances','financeMaster','customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $finance = Finance::findOrFail($id);
       $finance->update([

            'ref_mobile_no' => $request->ref_mobile_no,
            'ref_city'=> $request->ref_city,
            'ref_name' => $request->ref_name,
            'file_no' => $request->file_no,
           'customer_id' => $request->customer_id,
            'mobile_security_charges' => $request->mobile_security_charges,
            'finances_master_id' => $request->finances_master_id,
            'downpayment' => $request->downpayment,
            'processing_fee' => $request->processing_fee,
            'emi_charger' => $request->emi_charger,
            'finance_amount' => is_numeric($request->finance_amount) ? $request->finance_amount : 0,
            'month_duration' => is_numeric($request->month_duration) ? $request->month_duration : 0,
            'emi_value' => $request->permonthvalue,
            'penalty' => $request->penalty,
            'dedication_date' => $request->deduction_date,
            'finance_year' => $request->finance_year ?? date('Y')

        ]);

            return redirect()->route('admin.finance.index')->with('success', 'Finance updated successfully.');

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Finance $finance, $id)
    {
        $data =Finance::findOrFail($id);
        $data->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Finance deleted successfully.',
            ]
        );
    }
}
