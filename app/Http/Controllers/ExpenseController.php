<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Expense;
use App\Models\Category;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with('category');

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('date', date('m', strtotime($request->month)))
                  ->whereYear('date', date('Y', strtotime($request->month)));
        }

        $expenses = $query->orderBy('date', 'desc')->get();
        $categories = Category::all();
        
        $total_expenses = $expenses->sum('amount');
        
        // Data for Chart
        $expenses_by_category = Expense::selectRaw('category_id, sum(amount) as total')
            ->groupBy('category_id')
            ->pluck('total', 'category_id');
            
        return view('expenses.index', compact('expenses', 'categories', 'total_expenses', 'expenses_by_category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        Expense::create($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    public function import()
    {
        return view('expenses.import');
    }

    public function parse(Request $request)
    {
        $request->validate(['bulk_text' => 'required|string']);
        
        $lines = explode("\n", $request->bulk_text);
        $parsedExpenses = [];
        $categories = Category::all();

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Try to find amount (regex for number, allowing decimals)
            // Strategy: Look for the number, assume rest is description
            if (preg_match('/(\d+(\.\d{1,2})?)/', $line, $matches)) {
                $amount = $matches[1];
                // Remove amount from string to get description
                $description = trim(str_replace($amount, '', $line));
                // Clean up non-alphanumeric chars from edges if needed
                $description = trim($description, " -:\t");
            } else {
                $amount = 0;
                $description = $line;
            }

            // Guess Category
            $matchedCategoryId = null;
            $lowerDesc = strtolower($description);
            foreach ($categories as $cat) {
                if (str_contains($lowerDesc, strtolower($cat->name))) {
                    $matchedCategoryId = $cat->id;
                    break; 
                }
            }

            $parsedExpenses[] = [
                'date' => date('Y-m-d'),
                'description' => $description ?: 'Expense', // Fallback
                'amount' => $amount,
                'category_id' => $matchedCategoryId
            ];
        }

        return view('expenses.import_preview', compact('parsedExpenses', 'categories'));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'expenses' => 'required|array',
            'expenses.*.description' => 'required|string|max:255',
            'expenses.*.amount' => 'required|numeric',
            'expenses.*.date' => 'required|date',
            'expenses.*.category_id' => 'required|exists:categories,id',
        ]);

        foreach ($request->expenses as $expenseData) {
            Expense::create($expenseData);
        }

        return redirect()->route('expenses.index')->with('success', count($request->expenses) . ' expenses imported successfully.');
    }
}
