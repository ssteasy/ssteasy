<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            width: 800px;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        aside {
            width: 30%;
            background: #11456D;
            color: white;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        aside img.avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin-bottom: 16px;
        }
        aside h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }
        aside p.name {
            margin: 4px 0 16px;
            font-size: 14px;
            text-transform: uppercase;
            text-align: center;
        }
        aside .info {
            width: 100%;
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 16px;
            margin-top: 8px;
            font-size: 12px;
            line-height: 1.4;
        }
        aside .info p {
            margin: 8px 0;
        }
        aside .company {
            margin-top: auto;
            text-align: center;
            font-size: 12px;
        }
        aside .company img {
            margin-top: 8px;
            width: 60px;
            height: 60px;
            object-fit: contain;
            background: white;
            padding: 4px;
            border-radius: 4px;
        }

        main {
            width: 70%;
            background: white;
            padding: 24px;
            box-sizing: border-box;
            color: #333;
        }
        main h1 {
            margin: 0;
            font-size: 22px;
            text-align: center;
        }
        main p.subtitle {
            margin: 4px 0 24px;
            font-size: 14px;
            text-align: center;
            color: #666;
        }
        section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            border-bottom: 2px solid #4DB2E1;
            display: inline-block;
            margin-bottom: 8px;
        }
        .two-cols, .three-cols {
            display: flex;
            justify-content: space-between;
        }
        .two-cols > div, .three-cols > div {
            box-sizing: border-box;
        }
        .two-cols > div {
            width: 48%;
        }
        .three-cols > div {
            width: 30%;
        }
        .field-label {
            font-size: 11px;
            color: #555;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .field-value {
            font-size: 12px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- PANEL IZQUIERDO --}}
        <aside>
            @if($user->profile_photo_path)
                <img class="avatar" src="{{ Storage::url($user->profile_photo_path) }}" alt="Avatar">
            @else
                <div class="avatar" style="background:#4DB2E1;color:white;display:flex;align-items:center;justify-content:center;font-size:36px;">
                    {{ strtoupper(substr($user->primer_nombre,0,1)) }}
                </div>
            @endif

            <h2>{{ $user->primer_nombre }} {{ $user->segundo_nombre }}</h2>
            <p class="name">{{ $user->primer_apellido }} {{ $user->segundo_apellido }}</p>

            <div class="info">
                <p><strong>Email:</strong><br>{{ $user->email }}</p>
                <p><strong>Teléfono:</strong><br>{{ $user->telefono }}</p>
                <p><strong>Dirección:</strong><br>{{ $user->direccion }}</p>
                <p><strong>Zona:</strong> {{ $user->zona }}</p>
            </div>

            <div class="company">
                <p><strong>Empresa</strong><br>{{ $user->empresa?->nombre }}</p>
                @if($user->empresa?->logo)
                    <img src="{{ Storage::url($user->empresa->logo) }}" alt="Logo">
                @endif
            </div>
        </aside>

        {{-- PANEL DERECHO --}}
        <main>
            <h1>Hoja de Vida</h1>
            <p class="subtitle">
                {{ $user->primer_nombre }} {{ $user->segundo_nombre }}
                {{ $user->primer_apellido }} {{ $user->segundo_apellido }}
            </p>

            {{-- Perfil --}}
            <section>
                <div class="section-title">Perfil</div>
                <p class="field-value">
                    <strong>Roles:</strong> {{ $user->roles->pluck('name')->implode(', ') }}.<br>
                    <strong>Contrato:</strong> {{ $user->tipo_contrato }} desde {{ optional($user->fecha_inicio)->format('Y-m-d') }}.
                </p>
            </section>

            {{-- Detalles personales --}}
            <section class="two-cols">
                <div>
                    <div class="field-label">Documento</div>
                    <div class="field-value">{{ $user->tipo_documento }} — {{ $user->numero_documento }}</div>
                </div>
                <div>
                    <div class="field-label">Contrato & Sede</div>
                    <div class="field-value">
                        {{ $user->tipo_contrato }} / {{ $user->modalidad }}<br>
                        Sede: {{ $user->sede?->nombre }}
                    </div>
                </div>
            </section>

            {{-- Ubicación --}}
            <section class="three-cols">
                <div>
                    <div class="field-label">País</div>
                    <div class="field-value">
                        {{ $user->pais_dane }}
                        @if($user->pais?->nombre)— {{ $user->pais->nombre }}@endif
                    </div>
                </div>
                <div>
                    <div class="field-label">Departamento</div>
                    <div class="field-value">
                        {{ $user->departamento_dane }}
                        @if($user->departamento?->nombre)— {{ $user->departamento->nombre }}@endif
                    </div>
                </div>
                <div>
                    <div class="field-label">Municipio</div>
                    <div class="field-value">
                        {{ $user->municipio_dane }}
                        @if($user->municipio?->nombre)— {{ $user->municipio->nombre }}@endif
                    </div>
                </div>
            </section>

            {{-- Seguridad social y adicional --}}
            <section class="two-cols">
                <div>
                    <div class="field-label">Seguridad Social</div>
                    <div class="field-value">
                        EPS: {{ $user->eps }}<br>
                        ARL: {{ $user->arl }}<br>
                        AFP: {{ $user->afp }}<br>
                        IPS: {{ $user->ips ?? '—' }}
                    </div>
                </div>
                <div>
                    <div class="field-label">Información Adicional</div>
                    <div class="field-value">
                        Sexo: {{ $user->sexo }}<br>
                        Nivel de Riesgo: {{ $user->nivel_riesgo }}
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
