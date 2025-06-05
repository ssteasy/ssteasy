<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            text-align: center;
            padding: 2cm;
            border: 10px solid #555;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 1rem;
        }
        .titulo {
            font-size: 2rem;
            margin-top: 1rem;
            font-weight: bold;
        }
        .subtitulo {
            font-size: 1.2rem;
            margin-top: 1rem;
            color: #555;
        }
        .contenido {
            margin-top: 2rem;
            font-size: 1rem;
            line-height: 1.5;
        }
        .footer {
            margin-top: 3rem;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Logo de la empresa --}}
        <img class="logo" src="{{ $logoPath }}" alt="Logo Empresa">

        <div class="titulo">
            CERTIFICADO DE APROBACIÓN
        </div>

        <div class="subtitulo">
            {{ $curso->nombre_capacitacion }}
        </div>

        <div class="contenido">
            <p>Otorgado a:</p>
            <h2>{{ $user->primer_nombre }} {{ $user->primer_apellido }}</h2>
            <p>Número de documento: {{ $user->numero_documento }}</p>

            <p>Por haber completado el curso con una calificación de <strong>{{ $porcentaje }}%</strong>.</p>

            <p>Fechas del curso: {{ $curso->fecha_inicio?->format('d/m/Y') ?? '—'}} 
               al {{ $curso->fecha_fin?->format('d/m/Y') ?? '—'}}.</p>

            <p>Emitido el {{ $hoy->format('d/m/Y') }}.</p>

            <p>Código único de certificado: <strong>{{ $codigoUnico }}</strong></p>
        </div>

        <div class="footer">
            Firma autorizada y sello de la empresa
        </div>
    </div>
</body>
</html>
