<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LogoutResponse as Responsable;
use Illuminate\Http\RedirectResponse;

class LogoutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        // 1. Αποσύνδεση του χρήστη
        Auth::logout();

       // 2. Καταστροφή της συνεδρίας
        $request->session()->invalidate();
        // 3. Δημιουργία νέου token για την ασφάλεια
        $request->session()->regenerateToken();
        // 4. Ανακατεύθυνση στην αρχική σελίδα
        return redirect()->route('/');
    }
}