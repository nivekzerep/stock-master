<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pedido #{{ $order->folio }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Comprobante de Pedido</h1>
        <p><strong>Folio:</strong> {{ $order->folio }}</p>
    </div>

    <div class="details">
        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y H:i') }}</p>
        <p><strong>Generado por:</strong> Sistema de Gestión de Inventarios</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Producto</th>
                <th>CANT.</th>
                <th>P. UNITARIO</th>
                <th>SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $detail)
            <tr>
                <td>{{ $detail->product->sku ?? 'ND' }}</td>
                <td>{{ $detail->product->name ?? 'Producto Eliminado' }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>${{ number_format($detail->unit_price, 2) }}</td>
                <td>${{ number_format($detail->quantity * $detail->unit_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <p>Total: ${{ number_format($order->total, 2) }}</p>
    </div>
</body>
</html>