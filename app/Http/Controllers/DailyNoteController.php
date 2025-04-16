<?php

namespace App\Http\Controllers;

use App\Models\DailyNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DailyNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dailyNotes = DailyNote::all();
        return view('notes.index', compact('dailyNotes'));
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dailyNote = new DailyNote();
        $dailyNote->title = $request->title;
        $dailyNote->description = $request->description;
        $dailyNote->date = now();
        $dailyNote->save();

        Session::flash('success', 'Note added successfully');
        return redirect()->route('admin.daily-notes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyNote $dailyNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyNote $dailyNote,$id)
    {
        $dailyNote = DailyNote::find($id);
        return view('notes.edit', compact('dailyNote'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyNote $dailyNote,$id)
    {
        $dailyNote = DailyNote::find($id);
        $dailyNote->title = $request->title;
        $dailyNote->description = $request->description;
        $dailyNote->date = now();
        $dailyNote->save();

        return redirect()->route('admin.daily-notes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyNote $dailyNote,$id)
    {
        $dailyNote = DailyNote::find($id);
        $dailyNote->delete();

        return response()->json(['status' => 'success', 'message' => 'Note deleted successfully.']);

    }
}
