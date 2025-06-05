<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\{Capacitacion, Encuesta, Certificado};
use App\Policies\{CapacitacionPolicy, EncuestaPolicy, CertificadoPolicy};

use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Mapeo modelo → policy.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Capacitacion::class => CapacitacionPolicy::class,
        Encuesta::class     => EncuestaPolicy::class,
        Certificado::class  => CertificadoPolicy::class,
    ];

    /** --------------------------------------------------------------------- */
    public function register(): void
    {
        //
    }

    /** --------------------------------------------------------------------- */
    public function boot(): void
    {
        /* 1 ───────────────────────── Policies */


        /* 2 ───────────────────────── Gates-alias para acciones personalizadas */
        Gate::define('participate-capacitacion', [CapacitacionPolicy::class, 'participate']);
        Gate::define('respond-encuesta',        [EncuestaPolicy::class,     'respond']);
        Gate::define('generate-certificados',   [CapacitacionPolicy::class, 'generateCertificates']);

        /* 3 ───────────────────────── Superadmin siempre con todos los permisos */
        $superadmin = Role::firstOrCreate([
            'name'       => 'superadmin',
            'guard_name' => 'web',
        ]);
        $superadmin->syncPermissions(Permission::all());

        /* 4 ───────────────────────── CSS global de tu app dentro de Filament */
        Filament::serving(function () {
            Filament::registerRenderHook(
                'head.start',
                fn () => '<link rel="stylesheet" href="https://app.ssteasy.com/css/app.css">'
            );
        });
    }

}
