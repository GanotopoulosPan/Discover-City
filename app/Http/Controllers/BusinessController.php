<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use App\Models\CustomList;
use App\Models\Sight;

class BusinessController extends Controller
{
    public function index()
    {
        /*To withDetails() είναι ένα scope που έχουμε ορίσει στο μοντέλο Business για να φέρνει 
         τον μέσο όρο των αξιολογήσεων, το πλήθος των reviews και αν είναι στα αγαπημένα του χρήστη (αν είναι συνδεδεμένος)
         ώστε να μην επαναλαμβάνεται ο ίδιος κώδικας συνέχεια*/

         // ------Εμφάνιση στην αρχική σελίδα: ------//
    
        // 1. Τελευταίες 3 επιχειρήσεις (Με έτοιμο μέσο όρο, πλήθος reviews και έλεγχο αγαπημένων)
        $latestBusinesses = Business::withDetails()->latest()->take(3)->get();

        // 2. Δημοφιλή μέρη (Βάσει αξιολογήσεων)
        $popularBusinesses = Business::withDetails()
            ->has('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(3)
            ->get();

        // 3. Αν δεν έχουμε 3 δημοφιλή μέρη, συμπληρώνουμε με τυχαία
        if ($popularBusinesses->count() < 3) {
            $existingIds = $popularBusinesses->pluck('id')->toArray();
            $fallback = Business::withDetails()
                ->whereNotIn('id', $existingIds)
                ->inRandomOrder()
                ->take(3 - $popularBusinesses->count())
                ->get();
                
            $popularBusinesses = $popularBusinesses->merge($fallback);
        }

        // Pins για τον χάρτη
        $mapBusinesses = Business::all(); 

        // Είδες πρόσφατα
        $recentIds = session()->get('recently_viewed', []);
        $recentlyViewed = collect(); 

        if (!empty($recentIds)) {
            $cleanIds = array_filter($recentIds, fn($id) => is_numeric($id));
            $cleanIds = array_slice($cleanIds, 0, 3); 

            if (!empty($cleanIds)) {
                $recentlyViewed = Business::withDetails()
                    ->whereIn('id', $cleanIds)
                    ->orderByRaw("FIELD(id, " . implode(',', $cleanIds) . ")")
                    ->take(3)
                    ->get();
            }
        }

        return view('index', compact('latestBusinesses', 'popularBusinesses', 'recentlyViewed', 'mapBusinesses'));
    }

    // Φόρμα προσθήκης νέας επιχείρησης
    public function create()
    {
        return view('form'); 
    }

    // Αποθήκευση νέας επιχείρησης
    public function store(Request $request)
    {
        // 1. Βασικός έλεγχος για τα πεδία της φόρμας
        $request->validate([
            'businessName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'tk' => 'required|string|size:5',
            'email' => 'required|email',
            'phone' => 'required|string',
            'businessCategory' => 'required|in:accommodation,food_drink,activity',
            'description' => 'required|string',
            'minidescription' => 'required|string|max:75',
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'photos' => 'array|max:9',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // 2. Καθορισμός τύπου με βάση την κατηγορία
        $type = null;
        if ($request->businessCategory == 'accommodation') $type = $request->accType;
        if ($request->businessCategory == 'food_drink') $type = $request->foodType;
        if ($request->businessCategory == 'activity') $type = $request->actType;

        // 3. Φωτογραφίες
        $profilePhotoPath = null;
        
        // Αποθήκευση Εικόνας Προφίλ (εικόα προφίλ)
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('business_profiles', 'public');
        }

        // Αποθήκευση Φωτογραφιών Συλλογής (Πολλαπλές)
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoPaths[] = $photo->store('business_photos', 'public');
            }
        }

        // 4. Αποθήκευση (Ο Observer θα βρει αυτόματα τα Lat/Lon)
        Business::create([
            'user_id' => auth()->id(),
            'name' => $request->businessName,
            'address' => $request->address,
            'tk' => $request->tk,
            'phone' => $request->phone,
            'email' => $request->email,
            'ama' => $request->AMA,
            'area' => $request->area,
            'age_group' => $request->ageGroup,
            'website' => $request->website,
            'category' => $request->businessCategory,
            'type' => $type,
            'amenities' => $request->amenities,
            'duration' => $request->duration,
            'difficulty' => $request->difficulty,
            'schedule' => $request->schedule,
            'description' => $request->description,
            'minidescription' => $request->minidescription,
            'profile_photo' => $profilePhotoPath, 
            'photos' => $photoPaths,
        ]);

        // Αφού ο χρήστης έφτιαξε επιχείρηση, διαγράφουμε τυχόν παλιές του κριτικές
        \App\Models\Review::where('user_id', auth()->id())->delete();
        
        return redirect()->route('index')->with('success', 'Η επιχείρηση καταχωρήθηκε επιτυχώς!');
    }

    // Προβολή λεπτομερειών επιχείρησης
    public function myProfile()
    {
        $businesses = Business::where('user_id', auth()->id())->get();
        return view('my-business', compact('businesses'));
    }

    // Φόρμα επεξεργασίας επιχείρησης
    public function edit($id)
    {
        $business = Business::findOrFail($id);
        return view('form', compact('business'));
    }

    // Ενημέρωση επιχείρησης
    public function update(Request $request, Business $business)
    {
        abort_if($business->user_id !== auth()->id(), 403);

        $currentPhotos = $business->photos ?? [];
        $profilePhotoPath = $business->profile_photo; 

        // Ελέγχουμε αν ο χρήστης ανέβασε ΝΕΑ εικόνα προφίλ
        if ($request->hasFile('profile_photo')) {
            // Αν υπήρχε ήδη παλιά, την διαγράφουμε από τον δίσκο 
            if ($profilePhotoPath && Storage::disk('public')->exists($profilePhotoPath)) {
                Storage::disk('public')->delete($profilePhotoPath);
            }
            // Αποθηκεύουμε τη νέα εικόνα στον φάκελο business_profiles
            $profilePhotoPath = $request->file('profile_photo')->store('business_profiles', 'public');
        }
        // ------------------------------------------

        // Διαγραφή φωτογραφιών (Gallery)
        if ($request->has('delete_photos')) {
            foreach ($request->delete_photos as $photoToDelete) {
                if (Storage::disk('public')->exists($photoToDelete)) {
                    Storage::disk('public')->delete($photoToDelete);
                }
                $currentPhotos = array_filter($currentPhotos, fn($p) => $p !== $photoToDelete);
            }
            $currentPhotos = array_values($currentPhotos);
        }

        // Νέες φωτογραφίες (Gallery)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $currentPhotos[] = $photo->store('businesses', 'public');
            }
        }

        // Ο Observer θα ανανεώσει τα Lat/Lon αν άλλαξε η διεύθυνση ή ο ΤΚ
        $business->update([
            'name' => $request->businessName,
            'address' => $request->address,
            'tk' => $request->tk, 
            'area' => $request->area,
            'google_maps' => $request->googleMaps,
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website,
            'category' => $request->businessCategory,
            'type' => $request->businessCategory === 'accommodation' ? $request->accType : 
                    ($request->businessCategory === 'food_drink' ? $request->foodType : $request->actType),
            'amenities' => $request->amenities ?? [],
            'duration' => $request->duration,
            'difficulty' => $request->difficulty,
            'schedule' => $request->schedule,
            'description' => $request->description,
            'photos' => $currentPhotos,
            'profile_photo' => $profilePhotoPath,
            'minidescription' => $request->minidescription,
        ]);

        return redirect()->route('my.businesses')->with('success', 'Η επιχείρηση ενημερώθηκε!');
    }

    // Διαγραφή επιχείρησης
    public function destroy(Business $business)
    {
        abort_if($business->user_id !== auth()->id(), 403);
        $business->delete();
        return back()->with('success', 'Η επιχείρηση διαγράφηκε επιτυχώς.');
    }

    // Προβολή λεπτομερειών επιχείρησης
    public function show(Request $request, $id)
    {
        $businessId = is_object($id) ? $id->id : $id;
        $business = Business::findOrFail($businessId);
        
        
        // Φέρνουμε τα σχόλια με σελιδοποίηση (3 ανά σελίδα)
        $reviews = $business->reviews()->latest()->paginate(3);

        // Αν πατήθηκε βελάκι σελιδοποίησης, στέλνουμε πίσω ΜΟΝΟ το αρχείο των σχολίων
        if ($request->ajax()) {
            return view('partials.reviews', compact('business', 'reviews'))->render();
        }
        
        // Καταγραφή στα πρόσφατα 
        $recent = session()->get('recently_viewed', []);
        if (($key = array_search($businessId, $recent)) !== false) {
            unset($recent[$key]);
        }
        array_unshift($recent, $businessId);
        $recent = array_slice($recent, 0, 6);

        session()->put('recently_viewed', $recent);
        session()->save(); 

       
        $customLists = [];
        if (auth()->check()) {
            $customLists = CustomList::where('user_id', auth()->id())->get();
        }

        return view('business-details', compact('business', 'reviews', 'customLists'));
    }


    public function stay(Request $request)
    {
        $query = Business::withDetails()->where('category', 'accommodation');
        if ($request->filled('q')) {
            $ids = Business::search($request->q)->keys();
            $query->whereIn('id', $ids);
        }
        if ($request->has('type')) $query->whereIn('type', $request->type);
        if ($request->has('amenities')) {
            foreach ($request->amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }
        if ($request->has('area') && is_array($request->area)) $query->whereIn('area', $request->area);

        if ($request->filled('min_rating')) {
            $query->having('reviews_avg_rating', '>=', $request->min_rating);
        }
        $businesses = $query->get();
        
        // Φέρνουμε ΟΛΑ τα καταλύματα για τον χάρτη, ανεξάρτητα από τα checkboxes
        $mapBusinesses = Business::all();

        return view('stay', compact('businesses', 'mapBusinesses'));
    }
    // Οι μέθοδοι food_drink() και activities() είναι παρόμοιες με την stay(), απλά φιλτράρουν διαφορετική κατηγορία και έχουν διαφορετικό view
    public function food_drink(Request $request)
    {
        $query = Business::withDetails()->where('category', 'food_drink');
        if ($request->filled('q')) {
            $ids = Business::search($request->q)->keys();
            $query->whereIn('id', $ids);
        }
        if ($request->has('type')) $query->whereIn('type', $request->type);
        if ($request->has('amenities')) {
            foreach ($request->amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }
        if ($request->has('area') && is_array($request->area)) $query->whereIn('area', $request->area);

        if ($request->filled('min_rating')) {
            $query->having('reviews_avg_rating', '>=', $request->min_rating);
        }

        $businesses = $query->get();
        
        // Φέρνουμε ΟΛΑ τα μαγαζιά εστίασης για τον χάρτη
        $mapBusinesses = Business::all();

        return view('food-drink', compact('businesses', 'mapBusinesses'));
    }

    public function activities(Request $request)
    {
        $query = Business::withDetails()->where('category', 'activity');
        if ($request->filled('q')) {
            $ids = Business::search($request->q)->keys();
            $query->whereIn('id', $ids);
        }
        if ($request->has('type')) $query->whereIn('type', $request->type);
        if ($request->has('amenities')) {
            foreach ($request->amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }
        if ($request->has('area') && is_array($request->area)) $query->whereIn('area', $request->area);
        
        if ($request->filled('min_rating')) {
            $query->having('reviews_avg_rating', '>=', $request->min_rating);
        }

        $businesses = $query->get();
        
        // Φέρνουμε ΟΛΕΣ τις δραστηριότητες για τον χάρτη
        $mapBusinesses = Business::all();

        return view('activities', compact('businesses', 'mapBusinesses'));
    }

    // Η μέθοδος sights() είναι απλή γιατί τα αξιοθέατα δεν έχουν φίλτρα, οπότε απλά φέρνουμε όλα τα sights και τα στέλνουμε στο view
    public function sights()
    {
        $sights = Sight::all();
        return view('Sights', compact('sights'));
    }

    // Προβολή λεπτομερειών αξιοθέατου
    public function showSight($id)
    {
        $sight = \App\Models\Sight::findOrFail($id);
        
        // Επιστρέφουμε το view 'sight-details' με τα δεδομένα του αξιοθέατου 
        return view('sight-details', ['business' => $sight]);
    }
    public function globalSearch(Request $request)
    {
        // Το πεδίο αναζήτησης 'user_input' 
        $searchTerm = trim($request->input('user_input'));

        // Αν το πεδίο είναι κενό, επιστρέφουμε πίσω χωρίς να κάνουμε αναζήτηση
        if (empty($searchTerm)) {
            return redirect()->back();
        }

        // Μετατρέπουμε το search term σε lowercase για case-insensitive αναζήτηση
        $searchLower = mb_strtolower($searchTerm, 'UTF-8');

        //1. Ανάκτηση όλων των επιχειρήσεων με τα απαραίτητα relations (μέσος όρος, πλήθος reviews, αν είναι στα αγαπημένα)
        $businesses = Business::withDetails()->get();

        // 2. Φιλτράρισμα με χρήση μιας πιο καθαρής μεθόδου
        $results = $businesses->filter(function ($business) use ($searchLower) {
            $name = mb_strtolower($business->name, 'UTF-8');
            $type = mb_strtolower($business->type ?? '', 'UTF-8');

            // Ακριβές ταίριασμα
            if (str_contains($name, $searchLower) || str_contains($type, $searchLower)) {
                return true;
            }
            // Έξυπνο ταίριασμα (70% ομοιότητα με οποιαδήποτε λέξη στο όνομα)
            return collect(explode(' ', $name))->contains(function ($word) use ($searchLower) {
                similar_text($searchLower, $word, $percent);
                return $percent >= 70;
            });
        })->values();

        // 3. Διαχείριση αποτελεσμάτων
        if ($results->isEmpty()) {
            return redirect()->route('index')->with('error', 'Δεν βρέθηκε κάτι για: ' . $searchTerm);
        }
        
        // Επιλογή σωστού view
        $categoryViewMap = [
            'accommodation' => 'stay',
            'food_drink'    => 'food-drink',
            'activities'    => 'activities'
        ];

        // Αν το πρώτο αποτέλεσμα έχει κατηγορία, πηγαίνουμε σε αυτήν την σελίδα. Διαφορετικά, μένουμε στην αρχική.
        $firstCategory = $results->first()->category;

        // Αν δεν υπάρχει κατηγορία, επιστρέφουμε στην αρχική σελίδα με τα αποτελέσματα
        $viewName = $categoryViewMap[$firstCategory] ?? 'index';

        // Επιστρέφουμε το view με τα αποτελέσματα
        return view($viewName, [
            'businesses'    => $results
        ]);
    }
}