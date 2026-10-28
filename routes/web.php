<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\VolosInfoController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\AIPlannerController;
use App\Http\Controllers\SightController;
use Illuminate\Http\Request;

// =========================================================
// 1. ΔΗΜΟΣΙΕΣ ΔΙΑΔΡΟΜΕΣ (Δεν απαιτούν σύνδεση)
// =========================================================

// Αρχική Σελίδα & Αναζήτηση
Route::get('/', [BusinessController::class, 'index'])->name('index');
Route::get('/search', [BusinessController::class, 'globalSearch'])->name('global.search');

// Κατηγορίες (Index)
Route::get('/sights', [BusinessController::class, 'sights']);
Route::get('/food-drink', [BusinessController::class, 'food_drink']);
Route::get('/stay', [BusinessController::class, 'stay']);
Route::get('/activities', [BusinessController::class, 'activities']);

// Λεπτομέρειες (Show)
Route::get('/business/{id}', [BusinessController::class, 'show'])->name('business.show');
Route::get('/sights/{id}', [SightController::class, 'show'])->name('sights.show');

// Πληροφορίες Πόλης
Route::get('/info/{topic}', [VolosInfoController::class, 'show'])->name('volos.info');

// AI Planner
Route::post('/generate-itinerary', [AIPlannerController::class, 'generatePlan'])->name('ai.planner');

// =========================================================
// 2. ΕΛΕΓΧΟΣ ΤΑΥΤΟΤΗΤΑΣ (Authentication)
// =========================================================

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

require __DIR__.'/auth.php';

// =========================================================
// 3. ΔΙΑΔΡΟΜΕΣ ΧΡΗΣΤΩΝ & ΕΠΙΧΕΙΡΗΜΑΤΙΩΝ (Απαιτούν σύνδεση)
// =========================================================

Route::middleware(['auth'])->group(function () {
    
    // Προφίλ Χρήστη
    Route::get('/my-profile', function (Request $request) {
        return view('my-profile', ['user' => $request->user()]);
    })->name('my-profile');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Αγαπημένα (Favorites)
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorite/{business}', [FavoriteController::class, 'toggle']);
    Route::post('/favorites/{business}/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/favorites/{business}/status', [FavoriteController::class, 'checkStatus']);

    // Προσωπικές Λίστες Αγαπημένων
    Route::post('/lists/store', [ListController::class, 'store'])->name('lists.store');
    Route::post('/lists', [ListController::class, 'store']);
    Route::patch('/lists/{id}', [ListController::class, 'update']);
    Route::delete('/lists/{id}', [ListController::class, 'destroy']);

    // Αξιολογήσεις (Reviews)
    Route::post('/business/{business}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Διαχείριση των Επιχειρήσεών μου 
    Route::get('/my-businesses', [BusinessController::class, 'myProfile'])->name('my.businesses');
    Route::get('/form', [BusinessController::class, 'create'])->name('business.create');
    Route::post('/form', [BusinessController::class, 'store'])->name('business.store');
    Route::get('/form/{business}/edit', [BusinessController::class, 'edit'])->name('business.edit');
    Route::put('/form/{business}', [BusinessController::class, 'update'])->name('business.update');
    Route::delete('/form/{business}', [BusinessController::class, 'destroy'])->name('business.destroy');

});

// =========================================================
// 4. DEVELOPMENT ROUTES (Για δοκιμές και ανάπτυξη, όχι για παραγωγή)
// =========================================================

Route::get('/view-business', function () {
    $businesses = \App\Models\Business::all(); 
    return view('view-business', compact('businesses'));
});