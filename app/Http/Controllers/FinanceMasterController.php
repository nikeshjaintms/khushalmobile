<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\FinanceMaster;
use Illuminate\Http\Request;

class FinanceMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financeMasters = FinanceMaster::all();
        return view('finance_master.index', compact('financeMasters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance_master.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData=   $request->validate([
            'name' => 'required',

        ]);
        FinanceMaster::create($validatedData);
        return redirect()->route('admin.financeMaster.index')->with('success', 'finance name created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceMaster $financeMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceMaster $financeMaster, $id)
    {
        $data = FinanceMaster::find($id);
        return view('finance_master.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceMaster $financeMaster, $id)
    {
        $validatedData=   $request->validate([
            'name' => 'required',

        ]);

        $data = FinanceMaster::findOrFail($id);
        $data->update($validatedData);

        return redirect()->route('admin.financeMaster.index')->with('success', 'finance name updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceMaster $financeMaster, $id)
    {
        $data = FinanceMaster::findOrFail($id);
        $data->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Finance name deleted successfully.',
            ]
        );
    }
}
