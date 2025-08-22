<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    body  { font-family: DejaVu Sans; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th,td{ border:1px solid #000; padding:4px; vertical-align: top; }
    .title{ background:#ddd; font-weight:bold; text-align:center; }
</style>
</head>
<body>
<table>
    <tr><td colspan="4" class="title">FORMATO DE GESTIÓN DEL CAMBIO</td></tr>
    <tr><td><b>Proyecto / Empresa:</b> {{ $cambio->empresa->nombre }}</td>
        <td colspan="3"><b>Fecha solicitud:</b> {{ $cambio->fecha->format('d/m/Y') }}</td></tr>

    <tr><td colspan="4" class="title">Descripción del cambio</td></tr>
    <tr><td colspan="4">{{ $cambio->descripcion_cambio }}</td></tr>

    <tr><td colspan="4" class="title">Análisis de riesgos / Requisitos legales aplicables</td></tr>
    <tr><td colspan="4">{{ $cambio->analisis_riesgo }}<br>{{ $cambio->requisitos_legales }}</td></tr>

    <tr><td colspan="4" class="title">Requerimientos de Seguridad y Salud en el Trabajo</td></tr>
    <tr><td colspan="4">{{ $cambio->requerimientos_sst }}</td></tr>

    <tr><td colspan="4" class="title">Análisis del impacto del SG-SST</td></tr>
    <tr>
        <th>Peligros / Riesgos</th><th>Req. Legales</th>
        <th>Sistema Gestión</th><th>Procedimientos / Otros</th>
    </tr>
    @foreach($cambio->impactos as $imp)
        <tr>
            <td>{{ $imp->peligro_riesgo }}</td>
            <td>{{ $imp->requisitos_legales }}</td>
            <td>{{ $imp->sistema_gestion }}</td>
            <td>{{ $imp->procedimiento }} {{ $imp->otros }}</td>
        </tr>
    @endforeach

    <tr><td colspan="4" class="title">Planeación del cambio</td></tr>
    <tr>
        <th>Actividad</th><th>Responsable / Comunicar a</th>
        <th>Fecha ejecución</th><th>Fecha seguimiento</th>
    </tr>
    @foreach($cambio->actividades as $act)
        <tr>
            <td>{{ $act->actividad }}</td>
            <td>
                Resp: {{ $act->responsable->name }}<br>
                A: {{ optional($act->informarA)->name }}
            </td>
            <td>{{ $act->fecha_ejecucion->format('d/m/Y') }}</td>
            <td>{{ optional($act->fecha_seguimiento)->format('d/m/Y') }}</td>
        </tr>
    @endforeach
</table>

<p><b>Solicitante:</b> {{ $cambio->creador->name }}</p>
</body>
</html>
