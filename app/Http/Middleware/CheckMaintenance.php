<?php
// app/Http/Middleware/CheckMaintenance.php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;

class CheckMaintenance
{
    public function handle($request, Closure $next)
    {
        $inMaintenance = Setting::get('maintenance', false);

        /*  ⓐ  Siempre deja pasar la ruta de login + assets Filament   */
        if ($request->is([
            'admin/login',        // formulario de acceso
            'livewire/*',
            'filament/*',
            'vendor/filament/*',
            'build/*',
        ])) {
            return $next($request);
        }

        /*  ⓑ  Si no hay mantenimiento → todo normal                  */
        if (! $inMaintenance) {
            return $next($request);
        }

        /*  ⓒ  Hay mantenimiento: solo pasa el superadmin logueado    */
        if (auth()->check() && auth()->user()->hasRole('superadmin')) {
            return $next($request);
        }

        /*  ⓓ  El resto ve la página 503                              */
        return response()->view('errors.503', [], 503);
    }
}
