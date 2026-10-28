<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Business;
use App\Models\CustomList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    // Εμφάνιση της σελίδας με τα αγαπημένα
    public function index(Request $request)
    {
        // Έλεγχος: Αν ο χρήστης έχει επιχείρηση, τον πηγαίνουμε πίσω στην αρχική
        if (\App\Models\Business::where('user_id', auth()->id())->exists()) {
            return redirect()->route('index')->with('error', 'Οι λογαριασμοί επιχειρήσεων δεν έχουν πρόσβαση στη λίστα αγαπημένων.');
        }
        // Παίρνουμε τον τρέχοντα χρήστη και την επιλεγμένη λίστα από τα query parameters
        $userId = Auth::id();
        $selectedListId = $request->query('list', 'all'); 
        
        // Φέρνουμε όλες τις CustomLists του χρήστη με το πλήθος των αγαπημένων σε κάθε λίστα
        $customLists = CustomList::where('user_id', $userId)
            ->withCount('favorites')
            ->get();

        // Υπολογίζουμε το συνολικό πλήθος των μοναδικών αγαπημένων (distinct business_id) για τον χρήστη
        $totalFavoritesCount = Favorite::where('user_id', $userId)
            ->distinct('business_id')
            ->count('business_id');

        // Δημιουργούμε το βασικό query για τα αγαπημένα του χρήστη με τα απαραίτητα relations
        $query = Favorite::where('user_id', $userId)->with(['business.reviews']);
        $currentListTitle = "Όλα τα αγαπημένα";

        // Αν έχει επιλεγεί η γενική προβολή (all), φέρνουμε όλα τα αγαπημένα και κάνουμε unique με βάση το business_id για να μην έχουμε διπλοεμφανίσεις
        if ($selectedListId === 'all') {
            $favorites = $query->get()->unique('business_id');
        } else {
            /* Αν έχει επιλεγεί συγκεκριμένη λίστα, φιλτράρουμε τα αγαπημένα με βάση το custom_list_id. 
               Αν το selectedListId είναι 'null' ή κενό, σημαίνει ότι θέλουμε τα γενικά αγαπημένα που δεν ανήκουν σε καμία λίστα (custom_list_id = null) */
            $listId = ($selectedListId === '' || $selectedListId === 'null') ? null : $selectedListId;
            $query->where('custom_list_id', $listId);
            $list = $customLists->firstWhere('id', $selectedListId);
            if ($list) {
                $currentListTitle = $list->name;
            } elseif ($listId === null) {
                $currentListTitle = "Γενικά Αγαπημένα";
            }
            $favorites = $query->get();
        }

        /* Επιστρέφουμε το view με τα αγαπημένα, τις λίστες, το επιλεγμένο list id,
         το συνολικό πλήθος αγαπημένων και τον τίτλο της τρέχουσας λίστας */
        return view('favorites', compact(
            'favorites', 
            'customLists', 
            'selectedListId', 
            'totalFavoritesCount', 
            'currentListTitle'
        ));
    }

    // Toggle αγαπημένου (προσθήκη/αφαίρεση) για ένα business σε μια λίστα ή γενικά
    public function toggle(Request $request, Business $business)
    {
        try {
            $userId = Auth::id();
            $listId = $request->input('custom_list_id');

        
            if (empty($listId) || $listId === 'null' || $listId === 'undefined') {
                $listId = null;
            }

            // Αν το listId είναι null, σημαίνει ότι θέλουμε να κάνουμε toggle στα γενικά αγαπημένα (χωρίς λίστα)
            if (is_null($listId)) {
                $exists = Favorite::where('user_id', $userId)
                                ->where('business_id', $business->id)
                                ->whereNull('custom_list_id')
                                ->first();
                if ($exists) {
                    // Σβήσιμο από τα γενικά σημαίνει ότι αφαιρείται από όλες τις λίστες 
                    Favorite::where('user_id', $userId)
                            ->where('business_id', $business->id)
                            ->delete();
                    // Επιστρέφουμε ότι αφαιρέθηκε από τα γενικά και δεν είναι πλέον αγαπημένο
                    return response()->json(['status' => 'removed_globally', 'is_favorite' => false]);
                } else {
                    // Προσθήκη στα γενικά σημαίνει ότι προστίθεται ως γενικό αγαπημένο (χωρίς λίστα)
                    Favorite::create([
                        'user_id' => $userId,
                        'business_id' => $business->id,
                        'custom_list_id' => null
                    ]);
                    return response()->json(['status' => 'added_to_general', 'is_favorite' => true]);
                }
            }

            // Αν δεν είναι γενικό, τότε ελέγχουμε για τη συγκεκριμένη λίστα
            $favorite = Favorite::where('user_id', $userId)
                                ->where('business_id', $business->id)
                                ->where('custom_list_id', $listId)
                                ->first();

            if ($favorite) {
                //Σβήνοντας από μια λίστα, φεύγει ΜΟΝΟ από αυτή τη λίστα και όχι από τα γενικά
                $favorite->delete();
                return response()->json(['status' => 'removed_from_list', 'is_favorite' => false]);
            } 
            // Προσθήκη σε συγκεκριμένη λίστα, σημαίνει ότι προστίθεται μόνο σε αυτή τη λίστα (μπορεί να υπάρχει ήδη στα γενικά ή σε άλλη λίστα)
            else {
                Favorite::create([
                    'user_id' => $userId,
                    'business_id' => $business->id,
                    'custom_list_id' => $listId
                ]);
                return response()->json(['status' => 'added_to_list', 'is_favorite' => true]);
            }

        } catch (\Exception $e) {
            Log::error("Favorite Toggle Error: " . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    // Ελέγχουμε αν ένα business είναι ήδη αγαπημένο για τον τρέχοντα χρήστη και την επιλεγμένη λίστα
    public function checkStatus(Request $request, $businessId)
    {
        $userId = Auth::id();
        $listId = $request->query('custom_list_id');

        if (empty($listId) || $listId === 'null' || $listId === 'undefined') {
            $listId = null;
        }

        $exists = Favorite::where('user_id', $userId)
                        ->where('business_id', $businessId)
                        ->where('custom_list_id', $listId)
                        ->exists();

        return response()->json(['is_favorite' => $exists]);
    }
}