<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AURUM</title>
</head>
<body style="margin:0; padding:0; background-color:#EFEAE0; font-family: Arial, Helvetica, sans-serif;">

@php
    $corFaixa = match(true) {
        $percentual >= 100 => '#dc2626',
        $percentual >= 90 => '#d97706',
        default => '#B89050',
    };
    $corFundoAlerta = match(true) {
        $percentual >= 100 => '#fef2f2',
        $percentual >= 90 => '#fffbeb',
        default => '#faf6ec',
    };
    $tituloAlerta = match(true) {
        $percentual >= 100 => 'Limite anual ultrapassado',
        $percentual >= 90 => 'Atenção: limite quase no teto',
        default => 'Você atingiu 80% do limite anual',
    };
@endphp

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#EFEAE0; padding:32px 16px;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #E3DAC7;">

                <!-- Header -->
                <tr>
                    <td style="background-color:#1A1512; padding:28px 32px;">
                        <span style="font-size:22px; letter-spacing:4px; color:#C9A961; font-weight:bold;">AURUM</span>
                        <div style="font-size:11px; color:#9B8A6F; margin-top:4px; letter-spacing:0.5px;">
                            GESTÃO FINANCEIRA PARA MEI
                        </div>
                    </td>
                </tr>

                <!-- Faixa de status -->
                <tr>
                    <td style="background-color:{{ $corFundoAlerta }}; padding:16px 32px; border-bottom:3px solid {{ $corFaixa }};">
                        <span style="color:{{ $corFaixa }}; font-size:14px; font-weight:bold;">{{ $tituloAlerta }}</span>
                    </td>
                </tr>

                <!-- Conteúdo -->
                <tr>
                    <td style="padding:32px;">
                        <p style="margin:0 0 16px 0; font-size:15px; color:#2A2520;">Olá, {{ $nome }}!</p>

                        <p style="margin:0 0 24px 0; font-size:14px; color:#4A423A; line-height:1.6;">
                            @if ($percentual >= 100)
                                Seu faturamento ultrapassou o limite anual permitido para o MEI. É importante avaliar com seu contador a migração para ME/EPP o quanto antes.
                            @elseif ($percentual >= 90)
                                Seu faturamento está muito próximo do limite anual do MEI. Fique atento para não ultrapassar o teto permitido.
                            @else
                                Seu faturamento já atingiu uma parte significativa do limite anual do MEI. Vale acompanhar de perto os próximos lançamentos.
                            @endif
                        </p>

                        <!-- Barra de progresso -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="background-color:#EFEAE0; border-radius:6px; height:10px;">
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="{{ min($percentual, 100) }}%" style="height:10px;">
                                        <tr>
                                            <td style="background-color:{{ $corFaixa }}; border-radius:6px; height:10px; font-size:0; line-height:0;">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <p style="margin:-16px 0 24px 0; font-size:12px; color:#9B8A6F;">{{ $percentual }}% do limite anual utilizado</p>

                        <!-- Cards de valores -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="padding:14px 16px; background-color:#FAF7F0; border:1px solid #E3DAC7; border-radius:6px;">
                                    <div style="font-size:11px; color:#9B8A6F; text-transform:uppercase; letter-spacing:0.5px;">Faturamento (12 meses)</div>
                                    <div style="font-size:18px; color:#2A2520; font-weight:bold; margin-top:4px;">
                                        R$ {{ number_format($faturamentoAnual, 2, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                            <tr><td style="height:8px;"></td></tr>
                            <tr>
                                <td style="padding:14px 16px; background-color:#FAF7F0; border:1px solid #E3DAC7; border-radius:6px;">
                                    <div style="font-size:11px; color:#9B8A6F; text-transform:uppercase; letter-spacing:0.5px;">Limite anual permitido</div>
                                    <div style="font-size:18px; color:#2A2520; font-weight:bold; margin-top:4px;">
                                        R$ {{ number_format($limiteAnual, 2, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                            <tr><td style="height:8px;"></td></tr>
                            <tr>
                                <td style="padding:14px 16px; background-color:#FAF7F0; border:1px solid #E3DAC7; border-radius:6px;">
                                    <div style="font-size:11px; color:#9B8A6F; text-transform:uppercase; letter-spacing:0.5px;">Margem restante</div>
                                    <div style="font-size:18px; color:{{ $corFaixa }}; font-weight:bold; margin-top:4px;">
                                        R$ {{ number_format($restante, 2, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- Botão -->
                        <table role="presentation" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="border-radius:6px; background-color:#C9A961;">
                                    <a href="{{ $dashboardUrl }}"
                                       style="display:inline-block; padding:12px 28px; font-size:14px; font-weight:bold; color:#1A1512; text-decoration:none;">
                                        Ver Dashboard
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:20px 32px; background-color:#FAF7F0; border-top:1px solid #E3DAC7;">
                        <p style="margin:0; font-size:11px; color:#9B8A6F; line-height:1.5;">
                            Este é um alerta automático do AURUM para ajudar você a acompanhar a saúde financeira do seu negócio.
                            Este e-mail não substitui a apuração contábil oficial.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
