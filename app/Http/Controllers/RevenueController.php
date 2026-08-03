<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenueController extends Controller
{
    public function index()
    {
        $revenues = Revenue::where('user_id', Auth::id())
            ->orderByDesc('date')
            ->get();

        $total = $revenues->sum('value');

        return view('revenues.index', [
            'revenues' => $revenues,
            'total' => $total,
            'categories' => Revenue::CATEGORIES,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'category' => ['required', 'string', 'in:'.implode(',', Revenue::CATEGORIES)],
        ]);

        Revenue::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('revenues.index')->with('success', 'Receita adicionada com sucesso!');
    }

    public function destroy(Revenue $revenue)
    {
        abort_unless($revenue->user_id === Auth::id(), 403);

        $revenue->delete();

        return redirect()->route('revenues.index')->with('success', 'Receita removida.');
    }
}
