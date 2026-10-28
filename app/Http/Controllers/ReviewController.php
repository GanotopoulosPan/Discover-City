<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    /*Ο ReviewController διαχειρίζεται τις λειτουργίες που σχετίζονται με τις αξιολογήσεις των επιχειρήσεων,
     όπως η δημιουργία, η ενημέρωση και η διαγραφή των αξιολογήσεων.*/

    public function store(Request $request, $businessId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $exists = Review::where('user_id', auth()->id())
                        ->where('business_id', $businessId)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Έχετε ήδη κάνει αξιολόγηση!');
        }

        // Έλεγχος του σχολίου για ακατάλληλη φρασεολογία
        if ($error = $this->checkComment($request->comment)) {
            return back()->withErrors(['error' => $error]);
        }
        // Δημιουργία της νέας αξιολόγησης
        Review::create([
            'user_id'     => auth()->id(),
            'business_id' => $businessId,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
        ]);
        abort_if(\App\Models\Business::where('user_id', auth()->id())->exists(), 403, 'Μη εξουσιοδοτημένη ενέργεια.');
        return back()->with('success', 'Η αξιολόγησή σας υποβλήθηκε!');
    }

    //Ενημέρωση αξιολόγησης
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Δεν έχετε δικαίωμα επεξεργασίας.');
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Έλεγχος του σχολίου για ακατάλληλη φρασεολογία
        if ($error = $this->checkComment($request->comment)) {
            return back()->withErrors(['error' => $error]);
        }

        $review->update([
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Η αξιολόγηση ενημερώθηκε.');
    }

    // Διαγραφή αξιολόγησης
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Δεν έχετε δικαίωμα διαγραφής.');
        }
        
        $review->delete();
        
        return back()->with('success', 'Η αξιολόγηση διαγράφηκε.');
    }

    /* Βοηθητική μέθοδος για τον έλεγχο του σχολίου για ακατάλληλη φρασεολογία. 
       Επιστρέφει μήνυμα λάθους αν βρεθεί πρόβλημα, αλλιώς επιστρέφει null. */
    private function checkComment($comment)
    {
        // Αν δεν υπάρχει σχόλιο, προχωράμε κανονικά
        if (empty($comment)) {
            return null;
        }

        // 1. Έλεγχος Blacklist
        $blacklist = ['hate', 'stupid', 'χαζός', 'ηλίθιος', 'βλάκας'];
        $commentLower = mb_strtolower($comment, 'UTF-8'); // Χρησιμοποιούμε mb_strtolower για να υποστηρίξουμε πολυγλωσσικά σχόλια
        // Μετατρέπουμε το σχόλιο σε πεζά για να κάνουμε τον έλεγχο case-insensitive
        foreach ($blacklist as $badWord) {
            if (str_contains($commentLower, $badWord)) {
                return 'Το σχόλιό σας περιέχει ακατάλληλη φρασεολογία.';
            }
        }

        // 2. Έλεγχος OpenAI
        try {
            $moderation = OpenAI::moderations()->create([
                'input' => $comment,
            ]);

            if ($moderation->results[0]->flagged) {
                return 'Το σχόλιό σας παραβιάζει τους όρους χρήσης.';
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Error: ' . $e->getMessage());
        }

        return null; // Δεν βρέθηκε πρόβλημα
    }
}