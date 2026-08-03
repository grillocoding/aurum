<?php

namespace App\Services;

class DasCalculator
{
    // Valores fixos MEI (em R$)
    public const INSS_MEI = 70.60;
    public const ICMS_MEI = 1.00;
    public const ISS_MEI = 5.00;
    public const LIMITE_ANUAL_MEI = 81000.00;

    public const ATIVIDADES = [
        'comercio' => [
            'label' => 'Comércio',
            'icms' => true,
            'iss' => false,
            'exemplo' => 'Venda de mercadorias, lojas, revendas',
        ],
        'industria' => [
            'label' => 'Indústria / Artesanato',
            'icms' => true,
            'iss' => false,
            'exemplo' => 'Fabricação, artesanato, produção própria',
        ],
        'servicos' => [
            'label' => 'Serviços',
            'icms' => false,
            'iss' => true,
            'exemplo' => 'Consultoria, beleza, manutenção, TI',
        ],
        'comercio_servicos' => [
            'label' => 'Comércio + Serviços',
            'icms' => true,
            'iss' => true,
            'exemplo' => 'Venda de produtos e prestação de serviços',
        ],
    ];

    /**
     * Calcula a composição do DAS mensal para uma atividade.
     */
    public static function calcular(string $atividadeKey): array
    {
        $atividade = self::ATIVIDADES[$atividadeKey] ?? self::ATIVIDADES['comercio'];

        $inss = self::INSS_MEI;
        $icms = $atividade['icms'] ? self::ICMS_MEI : 0;
        $iss = $atividade['iss'] ? self::ISS_MEI : 0;
        $total = $inss + $icms + $iss;

        return [
            'atividade' => $atividade,
            'inss' => $inss,
            'icms' => $icms,
            'iss' => $iss,
            'total' => $total,
            'total_anual' => $total * 12,
        ];
    }

    public static function percentualLimite(float $faturamentoAnual): float
    {
        if ($faturamentoAnual <= 0) {
            return 0;
        }

        return min(($faturamentoAnual / self::LIMITE_ANUAL_MEI) * 100, 100);
    }
}
