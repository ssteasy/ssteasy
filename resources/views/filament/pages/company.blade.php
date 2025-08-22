<x-filament::page>
    {{-- ====== ESTILOS RESPONSIVE PARA MÓVILES ====== --}}
    <style>
        @media (max-width: 640px) {
            .company-card {
                width: 95% !important;
                margin: 1rem auto !important;
            }
            .company-card > header {
                flex-direction: column !important;
                align-items: flex-start !important;
                padding: 1rem !important;
                gap: .5rem !important;
            }
            .company-card > header div:first-child {
                width: 2rem !important;
                height: 2rem !important;
            }
            .company-card > header h1 {
                font-size: 1.25rem !important;
            }
            .company-card > header p {
                font-size: .7rem !important;
            }
            .company-card > div:nth-child(3) {
                display: grid !important;
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
                padding: 1rem !important;
            }
            .company-card img {
                width: 80% !important;
                height: auto !important;
            }
            .company-card > footer {
                padding: .75rem 1rem !important;
                font-size: .7rem !important;
            }
            .company-card > p {
                margin-top: 1rem !important;
                font-size: .7rem !important;
            }
        }
    </style>

    {{-- ====== TARJETA EMPRESA ====== --}}
    <div class="company-card" style="width:90%; max-width:72rem; margin:3rem auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:1.25rem; box-shadow:0 8px 32px rgba(0,0,0,.08); color:#374151; font-family:ui-sans-serif,system-ui,-apple-system,sans-serif; overflow:hidden;">
        <div style="height:6px; background:linear-gradient(90deg,#3b82f6 0%,#1d4ed8 100%);"></div>

        <header style="display:flex; align-items:center; gap:1rem; padding:2rem 2.5rem 1.6rem;">
            <div style="flex-shrink:0; height:2.5rem; width:2.5rem; border-radius:50%; background:#eff6ff;"></div>
            <div>
                <h1 style="margin:0; font-size:1.6rem; font-weight:600;">{{ $this->empresa->nombre }}</h1>
                <p style="margin:2px 0 0; font-size:.78rem; color:#6b7280;">Ficha corporativa</p>
            </div>
        </header>

        <div style="display:grid; grid-template-columns:minmax(260px,300px) 1fr; gap:2.5rem; padding:0 2.5rem 2.8rem;">
            {{-- Columna izquierda --}}
            <div style="text-align:center;display: flex;flex-direction: column;align-content: center;align-items: center;">
                <div style="margin-bottom:1.6rem; height:11rem; width:11rem; border-radius:50%; background:#f9fafb; display:flex; justify-content:center; align-items:center; overflow:hidden; box-shadow:0 0 0 3px #f3f4f6;">
                    <img
                        src="{{ $this->empresa->logo ? Storage::url($this->empresa->logo) : asset('images/company-placeholder.png') }}"
                        alt="Logo" style="width:100%; height:100%; object-fit:contain;">
                </div>

                <p>
                    {{ $this->empresa->razon_social ?: '—' }}<br>
                    <strong>NIT:</strong> {{ $this->empresa->nit }}
                </p>

                <span style="display:inline-block; margin-top:.75rem; padding:.28rem .8rem; font-size:.75rem; border-radius:.375rem; background:#f3f4f6; color:#374151;">
                    {{ $this->empresa->ciudad ?: 'Ciudad no registrada' }}
                </span>
            </div>

            {{-- Columna derecha --}}
            <section style="display:grid; gap:2rem 2.5rem;">
                <div>
                    <h3 style="margin:0 0 .8rem; font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#1d4ed8;">Contacto</h3>
                    <dl>
                        <div style="display:flex; justify-content:space-between; font-size:.875rem; margin-bottom:.45rem;">
                            <dt style="margin-right:.5rem; white-space:nowrap;">Teléfono</dt>
                            <dd style="text-align:right; word-break:break-all; max-width:60%;">{{ $this->empresa->telefono ?: '—' }}</dd>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:.875rem; margin-bottom:.45rem;">
                            <dt style="margin-right:.5rem; white-space:nowrap;">Correo</dt>
                            <dd style="text-align:right; word-break:break-all; max-width:60%;">{{ $this->empresa->email ?: '—' }}</dd>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:.875rem; margin-bottom:.45rem;">
                            <dt style="margin-right:.5rem; white-space:nowrap;">Sitio web</dt>
                            <dd style="text-align:right; word-break:break-all; max-width:60%;">
                                @if ($this->empresa->website)
                                    <a href="{{ $this->empresa->website }}" target="_blank">{{ Str::limit($this->empresa->website,24) }}</a>
                                @else — @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 style="margin:0 0 .8rem; font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#1d4ed8;">Dirección</h3>
                    <p>{{ $this->empresa->direccion ?: '—' }}</p>
                </div>

                <div>
                    <h3 style="margin:0 0 .8rem; font-size:.78rem; font-weight:600; text-transform:uppercase; letter-spacing:.05em; color:#1d4ed8;">Estado</h3>
                    <span style="display:inline-block; padding:.25rem 1rem; font-size:.75rem; font-weight:600; border-radius:.375rem; {{ $this->empresa->activo ? 'background:#dcfce7; color:#166534;' : 'background:#fee2e2; color:#b91c1c;' }}">
                        {{ $this->empresa->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                </div>
            </section>
        </div>

        <footer style="padding:1rem 2.5rem; font-size:.78rem; background:#fffbea; border-top:1px solid #fde68a; color:#92400e;">
            Si necesitas actualizar algún dato, solicita el cambio a un <strong>administrador</strong>.
        </footer>

        <p style="margin-top:2rem; text-align:center; font-size:.78rem; color:#9ca3af;">
            Última actualización: {{ $this->empresa->updated_at->format('d-m-Y H:i') }}
        </p>
    </div>
</x-filament::page>
