<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        switch ($request->get('filter_by')) {
            case 'last_week':
                $currentDate = now();
                $lastWeekDate = now()->subWeek();
                $expenses = Expense::whereBetween('date', [$lastWeekDate->toDateString(), $currentDate->toDateString()])
                    ->get();
                break;

            case 'last_month':
                $currentDate = now();
                $lastMonthDate = now()->subMonth();
                $expenses = Expense::whereBetween('date', [$lastMonthDate->toDateString(), $currentDate->toDateString()])
                    ->get();
                break;

            case 'last_three_months':
                $currentDate = now();
                $lastMonthDate = now()->subMonths(3);
                $expenses = Expense::whereBetween('date', [$lastMonthDate->toDateString(), $currentDate->toDateString()])
                    ->get();
                break;

            case 'custom':
                $expenses = Expense::whereBetween('date', [$request->get('start_date'), $request->get('end_date')])
                    ->get();
                break;

            default:
                $expenses = Expense::all();
                break;
        }

        return response()->json($expenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        $validated = $request->validated();
        $expense = Expense::create($validated);
        return response()->json($expense, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $validated = $request->validated();
        $expense->title = $validated['title'] ?? $expense->title;
        $expense->amount = $validated['amount'] ?? $expense->amount;
        $expense->date = $validated['date'] ?? $expense->date;
        $expense->category = $validated['category'] ?? $expense->category;
        $expense->update();
        return response()->json($expense);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json(null, 204);
    }
}
