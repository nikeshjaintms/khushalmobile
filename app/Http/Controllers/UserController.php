<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
