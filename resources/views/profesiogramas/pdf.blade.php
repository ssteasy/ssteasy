{{-- resources/views/profesiogramas/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 1rem; }
        .header img { max-height: 80px; }
        h2 { margin-bottom: 0; }
        .section { margin-top: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-top: .5rem; }
        th, td { border: 1px solid #333; padding: .25rem; text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        {{-- Si tienes logo: --}}
        {{-- <img src="{{ public_path('logo.png') }}" alt="Logo"> --}}
        <h2>Profesiograma de {{ $record->cargo->nombre }}</h2>
        <p><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <h3>Tareas asociadas</h3>
        <p>{!! nl2br(e($record->tareas)) !!}</p>
    </div>

    <div class="section">
        <h3>Funciones</h3>
        <p>{!! nl2br(e($record->funciones)) !!}</p>
    </div>

    <div class="section">
        <h3>Exámenes médicos requeridos</h3>
        <table>
            <thead>
                <tr>
                    <th>Tipo de examen</th>
                    <th>Periodicidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->profesiogramaExamenTipos as $item)
                    <tr>
                        <td>{{ $item->examenTipo->nombre }}</td>
                        <td>{{ $item->periodicidad_valor }} {{ $item->periodicidad_unidad }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="section">
        <h3>Exámenes médicos requeridos</h3>
        <table>
            <thead>
                <tr>
                    <th>Catálogo examen</th>
                    <th>Tipificación</th>
                    <th>Periodicidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($record->profesiogramaExamenTipos as $item)
                    <tr>
                        <td>{{ $item->examenTipo->nombre }}</td>
                        <td>{{ $item->tipificacion }}</td>
                        <td>{{ $item->periodicidad_valor }} {{ $item->periodicidad_unidad }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <h3>Vacunas requeridas</h3>
        @php
            $vacunas = $record->vacunas()->get();
        @endphp

        @if($vacunas->isEmpty())
            <p>No aplica</p>
        @else
            <ul>
                @foreach($vacunas as $vacuna)
                    <li>{{ $vacuna->nombre }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="section">
        <h3>Riesgos asociados</h3>
        <p>{!! nl2br(e($record->riesgos)) !!}</p>
    </div>

    <div class="section">
        <h3>Equipo de Protección Personal (EPP)</h3>
        @php
            $epps = $record->epps()->get();
        @endphp

        @if($epps->isEmpty())
            <p>No aplica</p>
        @else
            <ul>
                @foreach($epps as $item)
                    <li>{{ $item->nombre }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</body>
</html>
