<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->orderByDesc('date')
            ->get();

        $total = $expenses->sum('value');

        return view('expenses.index', [
            'expenses' => $expenses,
            'total' => $total,
            'categories' => Expense::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'category' => ['required', 'string', 'in:'.implode(',', Expense::CATEGORIES)],
        ]);

        Expense::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Despesa adicionada com sucesso!');
    }

    public function destroy(Expense $expense)
    {
        abort_unless($expense->user_id === Auth::id(), 403);

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Despesa removida.');
    }
}
