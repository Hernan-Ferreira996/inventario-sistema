<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333;">
    <h2>Actualización de tu envío {{ $envio->numero_envio }}</h2>
    <p>Hola {{ $envio->pedido->cliente->nombre ?? '' }},</p>
    <p>Te contamos que el estado de tu envío correspondiente al pedido <strong>{{ $envio->pedido->numero_referencia ?? '' }}</strong> es:</p>
    <p style="font-size: 18px; font-weight: bold;">{{ ucfirst($envio->estado) }}</p>

    <table style="border-collapse: collapse; margin-top: 16px;">
        <tr><td style="padding: 4px 12px 4px 0; color: #666;">Fecha de empaque</td><td>{{ $envio->fecha_empaque->format('d/m/Y') }}</td></tr>
        @if($envio->fecha_entrega)
        <tr><td style="padding: 4px 12px 4px 0; color: #666;">Fecha de entrega</td><td>{{ $envio->fecha_entrega->format('d/m/Y') }}</td></tr>
        @endif
        @if($envio->transportista)
        <tr><td style="padding: 4px 12px 4px 0; color: #666;">Transportista</td><td>{{ $envio->transportista }}</td></tr>
        @endif
        @if($envio->chofer)
        <tr><td style="padding: 4px 12px 4px 0; color: #666;">Chofer</td><td>{{ $envio->chofer }}</td></tr>
        @endif
        @if($envio->vehiculo_placa)
        <tr><td style="padding: 4px 12px 4px 0; color: #666;">Vehículo</td><td>{{ $envio->vehiculo_placa }}</td></tr>
        @endif
    </table>

    @if($envio->comentarios)
    <p style="margin-top: 16px;"><strong>Comentarios:</strong> {{ $envio->comentarios }}</p>
    @endif

    <p style="margin-top: 24px; color: #999; font-size: 12px;">Este es un mensaje automático, por favor no respondas a este correo.</p>
</body>
</html>
