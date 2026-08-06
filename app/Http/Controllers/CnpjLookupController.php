<?php

namespace App\Http\Controllers;

use App\Services\CnpjLookupService;
use Illuminate\Http\Request;

class CnpjLookupController extends Controller
{
    public function buscar(Request $request, CnpjLookupService $service)
    {
        $request->validate([
            'cnpj' => ['required', 'string'],
        ]);

        $dados = $service->buscar($request->input('cnpj'));

        if (! $dados) {
            return response()->json([
                'error' => 'CNPJ não encontrado ou inválido.',
            ], 404);
        }

        return response()->json($dados);
    }
}
