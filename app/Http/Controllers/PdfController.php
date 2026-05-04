<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function sertifikat()
    {
        $user = Auth::user();

        $pdf = Pdf::loadView('pdf.sertifikat', [
            'user' => $user
        ])
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('sertifikat.pdf'); // ← ganti ini
    }

    public function undangan()
    {
        $pdf = Pdf::loadView('pdf.undangan')
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('undangan.pdf'); // ← ganti ini
    }
}