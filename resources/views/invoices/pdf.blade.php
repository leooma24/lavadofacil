<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Helvetica', sans-serif; color: #1f2937; font-size: 12px; margin: 0; padding: 40px; }
        .header { display: table; width: 100%; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 3px solid #0ea5e9; }
        .header-left, .header-right { display: table-cell; vertical-align: top; }
        .header-right { text-align: right; }
        h1 { color: #0ea5e9; margin: 0; font-size: 28px; letter-spacing: -0.5px; }
        .label { color: #6b7280; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .value { font-size: 14px; font-weight: bold; color: #111827; margin-bottom: 12px; }
        .meta-table { width: 100%; margin-top: 30px; border-collapse: collapse; }
        .meta-table td { padding: 12px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .meta-table td:first-child { width: 50%; }
        .total-box { margin-top: 40px; padding: 25px; background: linear-gradient(135deg, #0ea5e9, #0284c7); color: white; border-radius: 8px; text-align: right; }
        .total-box .label { color: rgba(255,255,255,0.7); }
        .total-box .amount { font-size: 36px; font-weight: bold; margin: 5px 0; }
        .footer { margin-top: 60px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 10px; }
        .badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-paid { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <h1>LavadoFácil</h1>
            <p style="color: #6b7280; margin: 5px 0;">SaaS de fidelización para car washes</p>
        </div>
        <div class="header-right">
            <div class="label">Factura</div>
            <div class="value" style="font-size: 18px;">{{ $invoice->invoice_number }}</div>
            <span class="badge badge-{{ $invoice->status }}">
                @switch($invoice->status)
                    @case('paid') Pagada @break
                    @case('pending') Pendiente @break
                    @case('overdue') Vencida @break
                    @default {{ $invoice->status }}
                @endswitch
            </span>
        </div>
    </div>

    <table class="meta-table">
        <tr>
            <td>
                <div class="label">Cliente (Tenant)</div>
                <div class="value">{{ $invoice->tenant?->name ?? '—' }}</div>
                <div class="label">Contacto</div>
                <div class="value" style="font-size: 12px; font-weight: normal;">
                    {{ $invoice->tenant?->owner_name }}<br>
                    {{ $invoice->tenant?->owner_email }}<br>
                    {{ $invoice->tenant?->owner_phone }}
                </div>
            </td>
            <td>
                <div class="label">Plan</div>
                <div class="value">{{ $invoice->subscription?->plan?->name ?? '—' }}</div>
                <div class="label">Periodo</div>
                <div class="value" style="font-size: 12px; font-weight: normal;">
                    @if ($invoice->subscription)
                        {{ $invoice->subscription->starts_at->format('d/m/Y') }} —
                        {{ $invoice->subscription->ends_at->format('d/m/Y') }}
                    @endif
                </div>
                <div class="label">Fecha de emisión</div>
                <div class="value" style="font-size: 12px; font-weight: normal;">
                    {{ $invoice->created_at->format('d/m/Y') }}
                </div>
                @if ($invoice->paid_at)
                    <div class="label">Fecha de pago</div>
                    <div class="value" style="font-size: 12px; font-weight: normal;">
                        {{ $invoice->paid_at->format('d/m/Y H:i') }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    <div class="total-box">
        <div class="label">Total a pagar</div>
        <div class="amount">${{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</div>
    </div>

    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }} · LavadoFácil · {{ config('app.url') }}
    </div>
</body>
</html>
