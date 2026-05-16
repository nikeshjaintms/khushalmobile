<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DailyNote;
use App\Models\Dealer;
use App\Models\Deduction;
use App\Models\Product;
use App\Models\Transction;
use App\Models\Sale;
use App\Models\SaleTransaction;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get date filter values
        $dateFrom = $request->filled('date_from') ? Carbon::parse($request->date_from)->startOfDay() : Carbon::today()->startOfDay();
        $dateTo = $request->filled('date_to') ? Carbon::parse($request->date_to)->endOfDay() : Carbon::today()->endOfDay();
        
        // Store dates in session for consistency across AJAX calls
        session(['dashboard_date_from' => $dateFrom, 'dashboard_date_to' => $dateTo]);

        $productCount = Product::count();
        $dealerCount = Dealer::whereNull('deleted_at')->count();
        $customerCount = Customer::count();

        // Sales data with date filter
        $sales = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('total_amount') ?? 0.00;

        // Transaction data with date filter
        $transactionIn = Transction::where('type', 'in')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount') ?? 0.00;
            
        $transactionOut = Transction::where('type', 'out')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount') ?? 0.00;

        // Sales amount by payment method with date filter
        $salesAmountCash = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_method', '1')
            ->sum('total_amount');
            
        $salesAmountOnline = Sale::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_method', '2')
            ->sum('total_amount');

        // Sale transactions (payments received) with date filter
        $transactionInCash = SaleTransaction::where('payment_mode', '1')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount') ?? 0.00;
            
        $transactionInOnline = SaleTransaction::where('payment_mode', '2')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount') ?? 0.00;

        // Deduction amounts with date filter
        $deductionAmountCash = Deduction::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_mode', '1')
            ->sum('total');
            
        $deductionAmountOnline = Deduction::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('payment_mode', '2')
            ->sum('total');

        // Transaction out by payment method
        $transactionOutCash = Transction::where('payment_mode', '1')
            ->where('type', 'out')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount') ?? 0.00;
            
        $transactionOutOnline = Transction::where('payment_mode', '2')
            ->where('type', 'out')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount') ?? 0.00;

        // Purchase data for dashboard
        $totalPurchases = Purchase::whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('total') ?? 0.00;
            
        $purchaseCount = Purchase::whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        // Stock data
        $totalStock = PurchaseProduct::whereNull('status')
            ->orWhere('status', '!=', 'sold')
            ->count();
            
        $totalStockValue = PurchaseProduct::whereNull('status')
            ->orWhere('status', '!=', 'sold')
            ->sum('price') ?? 0.00;

        return response()->json([
            'product_count' => $productCount,
            'dealer_count' => $dealerCount,
            'customer_count' => $customerCount,
            'salesAmountCash' => number_format($salesAmountCash, 2),
            'salesAmountOnline' => number_format($salesAmountOnline, 2),
            'sales' => number_format($sales, 2),
            'transactionin' => number_format($transactionIn, 2),
            'transactionout' => number_format($transactionOut, 2),
            'transactionInCash' => number_format($transactionInCash, 2),
            'transactionInOnline' => number_format($transactionInOnline, 2),
            'deductionAmountCash' => number_format($deductionAmountCash, 2),
            'deductionAmountOnline' => number_format($deductionAmountOnline, 2),
            'transactionOutCash' => number_format($transactionOutCash, 2),
            'transactionOutOnline' => number_format($transactionOutOnline, 2),
            'total_purchases' => number_format($totalPurchases, 2),
            'purchase_count' => $purchaseCount,
            'total_stock' => $totalStock,
            'total_stock_value' => number_format($totalStockValue, 2),
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d'),
        ]);
    }

    public function displayTransactions(Request $request)
    {
        // Get date filter from session or request
        $dateFrom = $request->filled('date_from') 
            ? Carbon::parse($request->date_from)->startOfDay() 
            : (session('dashboard_date_from', Carbon::today()->startOfDay()));
            
        $dateTo = $request->filled('date_to') 
            ? Carbon::parse($request->date_to)->endOfDay() 
            : (session('dashboard_date_to', Carbon::today()->endOfDay()));

        // Transactions IN with date filter
        $transactionsIn = Transction::where('type', 'in')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('id', 'desc')
            ->get();

        // Transactions OUT with date filter
        $transactionsOut = Transction::where('type', 'out')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('id', 'desc')
            ->get();

        // Sales transactions with date filter - Fixed without eager loading relationship
        $salesTransactions = SaleTransaction::whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($transaction) {
                // dd($transaction);
                // Manually fetch sale data if needed
                $sale = Sale::find($transaction->invoice_id);
                return [
                    'id' => $transaction->id,
                    'amount' => $transaction->amount ?? 0.00,
                    'payment_mode' => $transaction->payment_mode,
                    'reference_no' => $transaction->reference_no,
                    'customer_name' => $sale ? ($sale->customer->name ?? 'N/A') : 'N/A',
                    'invoice_no' => $sale ? ($sale->invoice_no ?? 'N/A') : 'N/A',
                    'created_at' => $transaction->created_at
                ];
            });

        // EMI/Deduction transactions with date filter
        $emiTransactions = Deduction::with('customer')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($deduction) {
                return [
                    'id' => $deduction->id,
                    'amount' => $deduction->total,
                    'payment_mode' => $deduction->payment_mode,
                    'reference_no' => $deduction->refernce_no,
                    'customer_name' => $deduction->customer->name ?? 'N/A',
                    'created_at' => $deduction->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'transactionsIn' => $transactionsIn,
            'transactionsOut' => $transactionsOut,
            'transactions_count' => $transactionsIn->count(),
            'transactions_out_count' => $transactionsOut->count(),
            'sales_transactions' => $salesTransactions,
            'sales_transactions_count' => $salesTransactions->count(),
            'emi_transactions' => $emiTransactions,
            'emi_transactions_count' => $emiTransactions->count(),
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d'),
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
            'date' => now(),
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