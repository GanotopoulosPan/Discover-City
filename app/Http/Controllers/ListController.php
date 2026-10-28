<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomList;
use App\Models\Favorite; 

class ListController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50', // 'Οριο 50 χαρακτήρων για να μην χαλάει το UI
        ]);

        // Ελέγχουμε αν υπάρχει ήδη λίστα με το ίδιο όνομα για τον ίδιο χρήστη
        $exists = CustomList::where('user_id', auth()->id())
                            ->where('name', $request->name)
                            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Η λίστα υπάρχει ήδη!'], 422);
        }

        // Δημιουργούμε τη νέα λίστα
        $list = CustomList::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
        ]);

        // Επιστρέφουμε την νέα λίστα για να την προσθέσουμε δυναμικά
        return response()->json([
            'success' => true,
            'list' => $list
        ]);
    }

    // Διαγραφή λίστας
    public function destroy($id)
    {
        // Ελέγχουμε αν η λίστα ανήκει στον τρέχοντα χρήστη
        $list = CustomList::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        // Πριν διαγράψουμε τη λίστα, πρέπει να ενημερώσουμε τα αγαπημένα που ανήκουν σε αυτήν
        \App\Models\Favorite::where('custom_list_id', $list->id)->update(['custom_list_id' => null]);
        $list->delete();
        return response()->json(['success' => true]);
    }

    // Ενημέρωση ονόματος λίστας 
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:50']);
        // Ελέγχουμε αν υπάρχει ήδη λίστα με το ίδιο όνομα για τον ίδιο χρήστη
        $list = CustomList::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $list->update(['name' => $request->name]);

        return response()->json(['success' => true]);
    }
}