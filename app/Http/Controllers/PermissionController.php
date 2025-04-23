<?php

namespace App\Http\Controllers;

//use App\Models\Permission;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('permission.index',compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData=   $request->validate([
            'name' => 'required',

        ]);
        Permission::create([
            'name' => $request->post('name'),
            'guard_name' => 'web',
        ]);
        return redirect()->route('admin.permission.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission,$id)
    {
        $data = Permission::find($id);
        return view('permission.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission,$id)
    {
        $validatedData=   $request->validate([
            'name' => 'required',

        ]);
        $data = Permission::findOrFail($id);
        $data->update($validatedData);

        return redirect()->route('admin.permission.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission, $id)
    {
        $data = Permission::findOrFail($id);
        $data->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Permission deleted successfully.',
            ]
        );
    }
}
