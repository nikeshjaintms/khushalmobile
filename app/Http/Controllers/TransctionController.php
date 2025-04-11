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
    public function index()
    {
        $transctions = Transction::get();
        return view('transaction.index',compact('transctions'));
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
        $request->validate([
            'amount' => 'required',
            'type' => 'required',
            'remark' => 'string',
        ]);
        Transction::create($request->all());
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
            'amount' => 'required',
            'type' => 'required',
            'remark' => 'string',
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
