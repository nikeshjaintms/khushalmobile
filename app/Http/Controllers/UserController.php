<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required|',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        // dd(Auth::attempt($credentials));
        if(Auth::attempt($credentials)){

            return redirect()->route('dashboard');
        }
        else{
            return back()->with('error', 'Invalid credentials');
        }

    }

    public function logout(){
        Auth::logout();
        Session()->flush();

        return redirect()->route('login');
    }
    public function index()
    {
        $user = User::all();
        return view('user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $roles= Role::all();
        return view('user.create' , compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData=   $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',

        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $user= User::create($validatedData);
        $user->syncRoles($request->input('role'));
        return redirect()->route('admin.user.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, $id)
    {
        $data = User::find($id);
        $roles= Role::all();
        $roleName = $data->getRoleNames()->first();
        return view('user.edit', compact('data', 'roles', 'data','roleName'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( User $user,Request $request, string $id)
    {
        $validatedData=   $request->validate([
            'name' => 'required',
            'email' => 'required|email',

        ]);

        $data = User::findOrFail($id);
        $data->update($validatedData);
        $data->syncRoles($request->input('role'));

        return redirect()->route('admin.user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, $id)
    {
        $data = User::findOrFail($id);
        $data->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'User deleted successfully.',
            ]
        );
    }
}
