<?php

namespace App\Http\Controllers;

use App\Models\Transction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TransctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $transctions = Transction::get();
    //     return view('transaction.index',compact('transctions'));
    // }

    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)  // Add Request parameter
    {
        $query = Transction::query();
        
        // Apply filters
        if ($request->filled('amount')) {
            $query->where('amount', $request->amount);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('reference_no')) {
            $query->where('reference_no', 'LIKE', '%' . $request->reference_no . '%');
        }
        
        if ($request->filled('remark')) {
            $query->where('remark', 'LIKE', '%' . $request->remark . '%');
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Order by latest first
        $query->orderBy('created_at', 'desc');
        
        $transctions = $query->get();
        
        return view('transaction.index', compact('transctions'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'payement_mode' => 'required',
        //     'amount' => 'required',
        //     'type' => 'required',
        // ]);
        //  $request->validate([
        //      'payment_mode' => 'required',
        //      'amount' => 'required',
        //      'type' => 'required',
        //  ]);
        Transction::create([
            'payment_mode' => $request->payment_mode,
            'amount' => $request->amount,
            'type' => $request->type,
            'reference_no' => $request->reference_no ?? NULL,
            'remark' => $request->remark ?? NULL,
        ]);
        Session::flash('success','Transaction created successfully');
        return redirect()->route('admin.transaction.index')->with('success','Transaction created successfully');
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(Transction $transction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transction $transction, $id)
    {
        $transction = Transction::find($id);
        return view('transaction.edit',compact('transction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transction $transction, $id)
    {
        $request->validate([
            'payment_mode' => 'required',
            'amount' => 'required',
            'type' => 'required',

        ]);
        $update = Transction::find($id);
        $update->update($request->all());
        Session::flash('success','Transaction updated successfully');
        return redirect()->route('admin.transaction.index')->with('success','Transaction updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transction $transction, $id)
    {
        $delete = Transction::find($id);
        $delete->delete();
        Session::flash('success','Transaction deleted successfully');
        return response()->json(['status' => 'success', 'message' => 'Transaction deleted successfully']);
    }
}
