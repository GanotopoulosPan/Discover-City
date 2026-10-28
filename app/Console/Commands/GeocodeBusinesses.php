<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Business;

class GeocodeBusinesses extends Command
{
    protected $signature = 'app:geocode-businesses';
    protected $description = 'Geocode businesses using Nominatim API';

    public function handle()
    {
        // Βρίσκουμε όλα τα μαγαζιά που δεν έχουν γεωκωδικοποιηθεί (lat = null ή lat < 38.5 για να πιάσουμε και λάθος τιμές)
        $businesses = Business::whereNull('lat')
            ->orWhere('lat', '<', 38.5)
            ->get();

        // Για κάθε μαγαζί, προσπαθούμε να το γεωκωδικοποιήσουμε με διαφορετικές παραλλαγές της διεύθυνσης
        foreach ($businesses as $b) {
            $cleanAddress = trim($b->address);
            $tk = !empty($b->tk) ? trim($b->tk) : '';

            $this->info("Επεξεργασία: {$b->name} ({$cleanAddress} | ΤΚ: {$tk})");
            
            $found = false;

            // Φτιάχνουμε 3 διαφορετικές εκδοχές για την οδό 
            $streetOptions = [
                $cleanAddress,                                     // Πρώτη προσπάθεια: Κανονική διεύθυνση            
                trim(preg_replace('/[0-9]+/', '', $cleanAddress)), // Δεύτερη προσπάθεια: Αφαιρούμε τους αριθμούς (αν υπάρχουν)
                $b->name                                           // Τρίτη προσπάθεια: Χρησιμοποιούμε μόνο το όνομα του μαγαζιού (για πολύ ασαφείς διευθύνσεις)   
            ];  

            foreach ($streetOptions as $index => $street) {
                if ($found) break;

                $this->warn("Προσπάθεια " . ($index + 1) . ": {$street}, Βόλος");
                
                // Δημιουργούμε τις παραμέτρους για το Nominatim API
                $params = [
                    'street' => $street,
                    'city'   => 'Βόλος',
                    'county' => 'Μαγνησία',
                    'country'=> 'Ελλάδα',
                    'format' => 'json',
                    'limit'  => 1
                ];

                // Προσθέτουμε τον ταχυδρομικό κώδικα μόνο αν είναι έγκυρος (ξεκινάει με 38 για Βόλο)
                if (!empty($tk) && str_starts_with($tk, '38')) {
                    $params['postalcode'] = $tk;
                }

                try {
                    // Καλούμε το Nominatim API για γεωκωδικοποίηση
                    $response = Http::withHeaders([
                        'User-Agent' => 'DiscoverCityApp/1.0'
                    ])->get('https://nominatim.openstreetmap.org/search', $params);

                    $data = $response->json();

                    if (!empty($data)) {
                        $b->lat = $data[0]['lat'];
                        $b->lon = $data[0]['lon'];
                        $b->save();
                        $this->line("<info>✔ Επιτυχία:</info> {$b->lat}, {$b->lon}");
                        $found = true;
                        break;
                    }
                } catch (\Exception $e) {
                    $this->error("Σφάλμα API: " . $e->getMessage());
                }
                
                if (!$found) {
                    sleep(1.2); 
                }
            }

            if (!$found) {
                $this->error("Αποτυχία γεωκωδικοποίησης για: {$cleanAddress}");
            }

            sleep(1.2);
        }
        
        return Command::SUCCESS;
    }
}