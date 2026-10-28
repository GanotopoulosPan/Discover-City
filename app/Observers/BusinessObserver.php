<?php

namespace App\Observers;

use App\Models\Business;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BusinessObserver
{
    public function creating(Business $business)
    {
        $this->setCoordinates($business);
    }

    public function updating(Business $business)
    {
        // Ελέγχουμε αν άλλαξε η διεύθυνση ή ο ταχυδρομικός κώδικας
        if ($business->isDirty(['address', 'tk'])) {
            $this->setCoordinates($business);
        }
    }

    /* Η μέθοδος setCoordinates χρησιμοποιεί το OpenStreetMap Nominatim API για να πάρει τις συντεταγμένες 
       (lat, lon) μιας επιχείρησης με βάση τη διεύθυνση και τον ταχυδρομικό κώδικα. 
       Αν δεν υπάρχει διεύθυνση, απλά επιστρέφει χωρίς να κάνει τίποτα. 
       Αν η API κλήση είναι επιτυχής και επιστρέφει αποτελέσματα, τότε ενημερώνει τα πεδία lat και lon της επιχείρησης. */
    private function setCoordinates($business)
    {
        // Αν δεν υπάρχει διεύθυνση, δεν μπορούμε να πάρουμε συντεταγμένες, οπότε επιστρέφουμε
        if (!$business->address) return;
        // Δημιουργούμε το query για την API κλήση συνδυάζοντας τη διεύθυνση, τον ταχυδρομικό κώδικα και τη χώρα
        $query = "{$business->address}, {$business->tk}, Greece";
        // Κάνουμε την API κλήση στο Nominatim με το query και ένα User-Agent header για να αποφύγουμε μπλοκάρισμα από την υπηρεσία
        $response = Http::withHeaders([
            'User-Agent' => 'LaravelApp/1.0'
        ])->get('https://nominatim.openstreetmap.org/search', [
            'format' => 'json',
            'q' => $query,
            'limit' => 1
        ]);

        /* Αν η κλήση ήταν επιτυχής και επιστρέφει τουλάχιστον ένα αποτέλεσμα, ενημερώνουμε τα πεδία lat και lon
           της επιχείρησης με τις συντεταγμένες που μας έδωσε η API */
        if ($response->successful() && count($response->json()) > 0) {
            $business->lat = $response[0]['lat'];
            $business->lon = $response[0]['lon'];
        }
    }
}