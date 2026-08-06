<?php

namespace App\Services;

/**
 * Classifica um CNAE (Classificação Nacional de Atividades Econômicas) em uma
 * das categorias usadas pelo DasCalculator: comercio, industria, servicos ou
 * comercio_servicos.
 *
 * IMPORTANTE: esta é uma classificação heurística baseada na divisão do CNAE
 * (2 primeiros dígitos). O enquadramento tributário oficial do MEI depende de
 * regras mais específicas da Receita Federal / Comitê Gestor do Simples
 * Nacional, que podem variar por subclasse do CNAE. Trate o resultado como
 * uma SUGESTÃO — o usuário deve confirmar com um contador antes de assumir
 * como definitivo.
 */
class CnaeClassifier
{
    public static function classificar(string $cnae): string
    {
        $digitos = preg_replace('/\D/', '', $cnae);
        $divisao = (int) substr($digitos, 0, 2);

        return match (true) {
            // Agropecuária, indústria extrativa e de transformação (01-33)
            $divisao >= 1 && $divisao <= 33 => 'industria',

            // Comércio de veículos e reparação (mistura venda + serviço)
            $divisao === 45 => 'comercio_servicos',

            // Comércio por atacado e varejo (46-47)
            $divisao >= 46 && $divisao <= 47 => 'comercio',

            // Alojamento e alimentação — venda de produto + serviço (55-56)
            $divisao >= 55 && $divisao <= 56 => 'comercio_servicos',

            // Demais divisões (transporte, informação, financeiro, imóveis,
            // profissionais/técnicos, educação, saúde, entretenimento etc.)
            default => 'servicos',
        };
    }
}
