<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/*Ο ProfileController διαχειρίζεται τις λειτουργίες που σχετίζονται με το προφίλ του χρήστη,
 όπως η επεξεργασία των στοιχείων του προφίλ, η ενημέρωση των πληροφοριών και η διαγραφή του λογαριασμού.*/
class ProfileController extends Controller
{
    // Εμφάνιση της φόρμας επεξεργασίας προφίλ
    public function edit(Request $request): View
    {
        return view('my-profile', [
            'user' => $request->user(),
        ]);
    }

    // Ενημέρωση των στοιχείων του προφίλ
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // Διαγραφή του λογαριασμού του χρήστη
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Αποθηκεύουμε τον χρήστη πριν τον διαγράψουμε για να μπορέσουμε να τον αποσυνδέσουμε μετά τη διαγραφή
        $user = $request->user();

        Auth::logout();

        $user->delete();
        // Αφού διαγράψουμε τον χρήστη, καταστρέφουμε την συνεδρία για να αποτρέψουμε τυχόν προβλήματα με το session που ανήκε στον διαγραμμένο χρήστη
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
