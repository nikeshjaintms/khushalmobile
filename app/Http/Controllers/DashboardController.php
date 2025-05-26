<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DailyNote;
use App\Models\Product;
use App\Models\Transction;
use App\Models\Sale;
use App\Models\SaleTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $productCount = Product::count();

        $customerCount = Customer::count();

        $sales = Sale::whereDate('created_at', Carbon::today())
            ->sum('total_amount') ?? 0.00;

        $transactionIn = Transction::where('type', 'in')->whereDate('created_at', Carbon::today())->sum('amount') ?? 0.00;
        $transactionOut = Transction::where('type', 'out')->whereDate('created_at', Carbon::today())->sum('amount') ?? 0.00;

        return response()->json([
            'product_count' => $productCount,
            'customer_count' => $customerCount,
            'sales' => $sales,
            'transactionin' => $transactionIn,
            'transactionout' => $transactionOut,
        ]);
    }

    public function displayTransactions()
    {
        $transactionsIn = Transction::latest()->where('type', 'in')->orderBy('id', 'desc')->get();
        $transactionsOut = Transction::latest()->where('type', 'out')->orderBy('id', 'desc')->get();

        $sales_transactions = SaleTransaction::latest()->orderBy('id', 'desc')->get();
        return response()->json([
            'success' => true,
            'transactionsIn' => $transactionsIn,
            'transactionsOut' => $transactionsOut,
            'transactions_count' => $transactionsIn->count(),
            'transactions_out_count' => $transactionsOut->count(),
            'sales_transactions' => $sales_transactions,
            'sales_transactions_count' => $sales_transactions->count(),
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
