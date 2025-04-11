<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DailyNote;
use App\Models\Product;
use App\Models\Transction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $customerCount = Customer::count();
        $transaction = Transction::where('type','in')->sum('amount');

        return response()->json([
            'product_count' => $productCount,
            'customer_count' => $customerCount,
            'transaction' => $transaction,
        ]);
    }
    public function displaynotes()
    {
        $notes = DailyNote::latest()->get();

        return response()->json([
            'success' => true,
            'notes' => $notes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
        ]);

        $note = DailyNote::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => now(), // assuming your table has a `date` column
        ]);
        return response()->json([
            'success' => true,
            'note' => $note,
        ]);
    }

    public function destroy($id){
        $note = DailyNote::find($id);
        if($note){
            $note->delete();
            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully',
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Note not found',
            ]);
    }
}
}
