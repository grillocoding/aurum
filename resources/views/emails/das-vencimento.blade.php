<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AURUM</title>
</head>
<body style="margin:0; padding:0; background-color:#EFEAE0; font-family: Arial, Helvetica, sans-serif;">

@php
    $urgente = $diasRestantes === 0;
    $corFaixa = $urgente ? '#dc2626' : '#B89050';
    $corFundoAlerta = $urgente ? '#fef2f2' : '#faf6ec';
    $tituloAlerta = $urgente ? 'Seu DAS vence hoje' : "Faltam {$diasRestantes} dia(s) para o vencimento";
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
                            @if ($urgente)
                                Hoje é o último dia para pagar o DAS deste mês. Evite juros e multa por atraso.
                            @else
                                O vencimento do DAS deste mês é sempre no dia 20. Organize-se para não esquecer o pagamento.
                            @endif
                        </p>

                        <!-- Card do valor do DAS -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                            <tr>
                                <td style="padding:20px; background-color:#FAF7F0; border:1px solid #E3DAC7; border-radius:8px;" align="center">
                                    <div style="font-size:11px; color:#9B8A6F; text-transform:uppercase; letter-spacing:1px;">
                                        Valor do DAS — {{ $atividadeLabel }}
                                    </div>
                                    <div style="font-size:32px; color:#B89050; font-weight:bold; margin-top:8px;">
                                        R$ {{ number_format($valorDas, 2, ',', '.') }}
                                    </div>
                                    <div style="font-size:12px; color:#9B8A6F; margin-top:6px;">
                                        Vencimento: dia 20
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <!-- Botão -->
                        <table role="presentation" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="border-radius:6px; background-color:#C9A961;">
                                    <a href="{{ $dasUrl }}"
                                       style="display:inline-block; padding:12px 28px; font-size:14px; font-weight:bold; color:#1A1512; text-decoration:none;">
                                        Ver Simulador DAS
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <p style="margin:24px 0 0 0; font-size:12px; color:#9B8A6F; line-height:1.6;">
                            O pagamento pode ser feito pelo aplicativo MEI ou pelo portal do Simples Nacional.
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:20px 32px; background-color:#FAF7F0; border-top:1px solid #E3DAC7;">
                        <p style="margin:0; font-size:11px; color:#9B8A6F; line-height:1.5;">
                            Este é um lembrete automático do AURUM. O valor exibido considera a atividade cadastrada no seu perfil.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
