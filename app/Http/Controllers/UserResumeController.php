<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class UserResumeController extends Controller
{
    public function download(User $user)
    {
        // Autorización idéntica a la Page
        if (
            ! auth()->user()->hasRole('superadmin') &&
            !(auth()->user()->hasRole('admin') && auth()->user()->empresa_id === $user->empresa_id)
        ) {
            abort(403);
        }

        $pdf = Pdf::loadView('users.resume', compact('user'))
                  ->setPaper('a4', 'portrait');

        $filename = 'hoja_vida_' . $user->numero_documento . '.pdf';
        return $pdf->download($filename);
    }
}
