<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CnpjLookupService
{
    /**
     * Busca dados públicos de um CNPJ na BrasilAPI (gratuita, sem necessidade
     * de chave de API) e já sugere a categoria de atividade para o DAS.
     *
     * @return array{
     *     razao_social: ?string,
     *     nome_fantasia: ?string,
     *     cnae_fiscal: ?string,
     *     cnae_descricao: ?string,
     *     activity_type: ?string,
     *     logradouro: ?string,
     *     municipio: ?string,
     *     uf: ?string,
     *     cep: ?string,
     * }|null
     */
    public function buscar(string $cnpj): ?array
    {
        $cnpjLimpo = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpjLimpo) !== 14) {
            return null;
        }

        $response = Http::timeout(10)
            ->acceptJson()
            ->get("https://brasilapi.com.br/api/cnpj/v1/{$cnpjLimpo}");

        if (! $response->successful()) {
            return null;
        }

        $data = $response->json();
        $cnaePrincipal = $data['cnae_fiscal'] ?? null;

        $logradouro = trim(
            ($data['descricao_tipo_de_logradouro'] ?? '').' '.
            ($data['logradouro'] ?? '').
            (isset($data['numero']) && $data['numero'] ? ', '.$data['numero'] : ''),
        );

        return [
            'razao_social' => $data['razao_social'] ?? null,
            'nome_fantasia' => $data['nome_fantasia'] ?? null,
            'cnae_fiscal' => $cnaePrincipal !== null ? (string) $cnaePrincipal : null,
            'cnae_descricao' => $data['cnae_fiscal_descricao'] ?? null,
            'activity_type' => $cnaePrincipal !== null ? CnaeClassifier::classificar((string) $cnaePrincipal) : null,
            'logradouro' => $logradouro !== ',' ? ($logradouro ?: null) : null,
            'municipio' => $data['municipio'] ?? null,
            'uf' => $data['uf'] ?? null,
            'cep' => $data['cep'] ?? null,
        ];
    }
}
