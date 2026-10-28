<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // Εμφάνιση φόρμας εγγραφής
    public function showForm() {
        return view('register');
    }

    // Εγγραφή νέου χρήστη
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/'); 
    }


    // Εμφάνιση φόρμας σύνδεσης 
    public function showLoginForm() {
        return view('login');
    }

    // Σύνδεση χρήστη
    public function login(Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Έλεγχος αν ο χρήστης είναι admin
        if (Auth::user()->is_admin) {
            return redirect()->intended('/admin'); 
        }

        // Αν δεν είναι admin, πηγαίνει στην αρχική
        return redirect()->intended('/'); 
    }

    return back()->withErrors([
        'email' => 'Τα στοιχεία που δώσατε δεν ταιριάζουν με τα αρχεία μας.',
    ])->onlyInput('email');
}

    //Αποσύνδεση 

    public function logout(Request $request) {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}