<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        /* ─── Colores de marca ───────────────────────────────────────── */
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0.5rem;
            color: #333;
        }
        :root {
            --color-header-bg: #003366;   /* azul oscuro */
            --color-header-text: #ffffff; /* blanco */
            --color-th-bg: #004080;       /* azul medio */
            --color-planear-bg: #D0F0FD;  /* celeste claro */
            --color-planear-text: #003366;
            --color-pospuesta-bg: #FFF9C4;/* amarillo claro */
            --color-pospuesta-text: #8E7F00;
            --color-ejecutada-bg: #E8F5E9;/* verde claro */
            --color-ejecutada-text: #1B5E20;
            --color-noaplica-bg: #F5F5F5; /* gris muy claro */
            --color-noaplica-text: #4F4F4F;
            --color-frecuencia-mensual-bg: #4FC3F7;   /* info */
            --color-frecuencia-semestral-bg: #B2DFDB; /* teal claro */
            --color-frecuencia-trimestral-bg: #FFE082;/* ámbar */
            --color-frecuencia-anual-bg: #F8BBD0;     /* rosa claro */
            --color-frecuencia-eventual-bg: #FFE0B2;  /* naranja claro */
            --color-frecuencia-text: #333;
        }

        /* ─── Header ───────────────────────────────────────────────────── */
        header {
            background-color: var(--color-header-bg);
            color: var(--color-header-text);
            text-align: center;
            padding: 1rem 0.5rem;
            margin-bottom: 1rem;
        }
        header img.logo {
            max-height: 60px;
            margin-bottom: 0.5rem;
        }
        header h1 {
            margin: 0.25rem 0;
            font-size: 1.2rem;
        }
        header p {
            margin: 0.25rem 0;
            font-size: 0.75rem;
            line-height: 1.2;
        }

        /* ─── Tabla ────────────────────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 0.25rem;
            font-size: 0.65rem;
            overflow-wrap: break-word;
        }
        thead th {
            background-color: var(--color-th-bg);
            color: var(--color-header-text);
            text-align: center;
        }
        td.activity, td.responsable {
            min-width: 80px;
        }

        /* ─── Estadísticas ─────────────────────────────────────────────── */
        .stats {
            font-size: 0.75rem;
            margin-top: 1.5rem;
        }
        .stats h2, .stats h3 {
            margin-bottom: 0.25rem;
            color: var(--color-th-bg);
            font-size: 0.9rem;
        }
        .stats .item {
            display: inline-block;
            margin-right: 1rem;
            vertical-align: middle;
        }
        .stats .box {
            display: inline-block;
            width: 12px;
            height: 12px;
            vertical-align: middle;
            margin-right: 0.25rem;
            border: 1px solid #999;
        }
    </style>
</head>
<body>
    <header>
        <p>Hecho en ssteasy.com</p>
        @if(!empty($empresa->logo))
            <img style="max-width: 150px;height:auto;margin-top:20px;" src="{{ public_path('storage/'.$empresa->logo) }}" alt="Logo Empresa">
        @endif
        <h1>Plan de Trabajo Anual {{ $plan->year }}</h1>
        <p>
            <strong>{{ $empresa->nombre }}</strong><br>
            {{ $empresa->direccion }}<br>
            NIT: {{ $empresa->nit }} — Tel: {{ $empresa->telefono }}
        </p>
    </header>

    <h2 style="font-size:0.85rem; margin-bottom:0.25rem;">Actividades</h2>
    <table>
        <thead>
            <tr>
                <th>Actividad</th>
                <th>Responsable</th>
                <th>Frecuencia</th>
                @foreach (['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'] as $mes)
                    <th>{{ $mes }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($plan->actividades as $act)
                <tr>
                    <td class="activity">{{ $act->actividad }}</td>
                    <td class="responsable">{{ $act->responsable }}</td>
                    <td>{{ $act->frecuencia }}</td>
                    @foreach (['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'] as $key)
                        @php
                            $estado = $act->{"mes_{$key}"};
                            switch ($estado) {
                                case 'planear':
                                    $bg = 'var(--color-planear-bg)';
                                    $color = 'var(--color-planear-text)';
                                    $border = 'var(--color-planear-text)';
                                    break;
                                case 'pospuesta':
                                    $bg = 'var(--color-pospuesta-bg)';
                                    $color = 'var(--color-pospuesta-text)';
                                    $border = 'var(--color-pospuesta-text)';
                                    break;
                                case 'ejecutada':
                                    $bg = 'var(--color-ejecutada-bg)';
                                    $color = 'var(--color-ejecutada-text)';
                                    $border = 'var(--color-ejecutada-text)';
                                    break;
                                case 'no_aplica':
                                default:
                                    $bg = 'var(--color-noaplica-bg)';
                                    $color = 'var(--color-noaplica-text)';
                                    $border = 'var(--color-noaplica-text)';
                                    break;
                            }
                        @endphp
                        <td style="
                            background-color: {{ $bg }};
                            color: {{ $color }};
                            border: 1px solid {{ $border }};
                            text-align: center;
                        ">
                            {{ ucfirst(str_replace('_',' ',$estado)) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        // Estadísticas generales por estado
        $totales = ['planear'=>0,'pospuesta'=>0,'ejecutada'=>0,'no_aplica'=>0];
        foreach ($plan->actividades as $act) {
            foreach ($totales as $estado => &$count) {
                foreach (['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'] as $k) {
                    if ($act->{"mes_{$k}"} === $estado) {
                        $count++;
                    }
                }
            }
        }
        // Estadísticas por frecuencia
        $freqCounts = $plan->actividades->pluck('frecuencia')
                         ->map(fn($f) => ucfirst(strtolower($f)))
                         ->countBy();
    @endphp

    <div class="stats">
        <h2>Estadísticas Generales</h2>
        @foreach ($totales as $estado => $count)
            @php
                switch ($estado) {
                    case 'planear':   $boxBg = 'var(--color-planear-bg)';   $boxBorder = 'var(--color-planear-text)'; break;
                    case 'pospuesta': $boxBg = 'var(--color-pospuesta-bg)'; $boxBorder = 'var(--color-pospuesta-text)'; break;
                    case 'ejecutada': $boxBg = 'var(--color-ejecutada-bg)'; $boxBorder = 'var(--color-ejecutada-text)'; break;
                    default:          $boxBg = 'var(--color-noaplica-bg)'; $boxBorder = 'var(--color-noaplica-text)';
                }
            @endphp
            <div class="item">
                <span class="box" style="background-color: {{ $boxBg }}; border-color: {{ $boxBorder }};"></span>
                <strong style="color: {{ $boxBorder }};">
                    {{ ucfirst(str_replace('_',' ', $estado)) }}:
                </strong>
                {{ $count }}
            </div>
        @endforeach

        <h3>Actividades por Frecuencia</h3>
        @foreach ($freqCounts as $freq => $count)
            @php
                // Color según tipo de frecuencia
                $bgMap = [
                    'Mensual'    => 'var(--color-frecuencia-mensual-bg)',
                    'Semestral'  => 'var(--color-frecuencia-semestral-bg)',
                    'Trimestral' => 'var(--color-frecuencia-trimestral-bg)',
                    'Anual'      => 'var(--color-frecuencia-anual-bg)',
                    'Eventual'   => 'var(--color-frecuencia-eventual-bg)',
                ];
                $bg = $bgMap[$freq] ?? 'var(--color-noaplica-bg)';
                $border = '#999';
            @endphp
            <div class="item">
                <span class="box" style="background-color: {{ $bg }}; border-color: {{ $border }};"></span>
                <strong style="color: {{ $border }};">{{ $freq }}:</strong>
                {{ $count }}
            </div>
        @endforeach
    </div>
</body>
</html>
