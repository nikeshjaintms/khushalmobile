<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\financeMaster;
use Illuminate\Http\Request;

class FinanceMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financeMasters = financeMaster::all();
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
        financeMaster::create($validatedData);
        return redirect()->route('admin.financeMaster.index')->with('success', 'finance name created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(financeMaster $financeMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(financeMaster $financeMaster, $id)
    {
        $data = financeMaster::find($id);
        return view('finance_master.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, financeMaster $financeMaster, $id)
    {
        $validatedData=   $request->validate([
            'name' => 'required',

        ]);

        $data = financeMaster::findOrFail($id);
        $data->update($validatedData);

        return redirect()->route('admin.financeMaster.index')->with('success', 'finance name updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(financeMaster $financeMaster, $id)
    {
        $data = financeMaster::findOrFail($id);
        $data->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Finance name deleted successfully.',
            ]
        );
    }
}
