<?php

namespace App\Http\Controllers;

use App\Http\Requests\DealerRequest;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DealerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dealers = Dealer::all();
        return view('dealer.index', compact('dealers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dealer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData=   $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:dealers,phone',
            'city' => 'required',
        ]);
        Dealer::create($validatedData);
        return redirect()->route('admin.dealer.index')->with('success', 'Dealer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Dealer $dealer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dealer $dealer,$id)
    {
        $data = Dealer::find($id);
        return view('dealer.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $validatedData=   $request->validate([
            'name' => 'required',
            'phone' => [
                'required',
                Rule::unique('dealers', 'phone')->ignore($id, 'id'),
            ],
            'address' => 'required',
            'city' => 'required',
        ]);
        $dealer = Dealer::findOrFail($id);

        $dealer->update($validatedData);

        return redirect()->route('admin.dealer.index')->with('success', 'Dealer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dealer $dealer, $id)
    {
        $data = Dealer::findOrFail($id);
        $data->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Dealer deleted successfully.',
            ]
        );
    }
}
