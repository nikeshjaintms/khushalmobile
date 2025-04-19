<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DailyNote;
use App\Models\Product;
use App\Models\Transction;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $productCount = Product::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $customerCount = Customer::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $sales = Sale::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
            $transactionIn = Transction::where('type', 'in')->whereDate('created_at', Carbon::today())->sum('amount');
            $transactionOut = Transction::where('type', 'out')->whereDate('created_at', Carbon::today())->sum('amount');

        return response()->json([
            'product_count' => $productCount,
            'customer_count' => $customerCount,
            'sales' => $sales,
            'transactionin' => $transactionIn,
            'transactionout' => $transactionOut,
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

    public function destroy($id)
    {
        $note = DailyNote::find($id);
        if ($note) {
            $note->delete();
            return response()->json([
                'success' => true,
                'message' => 'Note deleted successfully',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Note not found',
            ]);
        }
    }
}
