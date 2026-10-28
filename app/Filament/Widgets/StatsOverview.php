<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Sight; 
use App\Models\Business;  
use App\Models\Review;   
use App\Models\Favorite;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            // 1η Καρτέλα: Συνολικοί Χρήστες
            Stat::make('Συνολικοί Χρήστες', User::count())
                ->description('Όλοι οι εγγεγραμμένοι χρήστες')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            // 1. Σύνολο Αξιοθέατων (από τον πίνακα sights)
            Stat::make('Καταχωρημένα Αξιοθέατα', Sight::count())
                ->description('Ιστορικά και Μυθολογικά σημεία')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('danger'),

            // 2.1. Επιχειρήσεις Εστίασης (διαβάζει τη στήλη category = 'food_drink')
            Stat::make('Επιχειρήσεις Εστίασης', Business::where('category', 'food_drink')->count())
                ->description('Καφέ, Μπαρ & Εστιατόρια')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning'),
            
            // 2.2. Επιχειρήσεις Εστίασης (διαβάζει τη στήλη category = 'activity')
            Stat::make('Επιχειρήσεις Δραστηριοτήτων', Business::where('category', 'activity')->count())
                ->description('Δραστηριότητες')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning'),

            // 2.3. Επιχειρήσεις Εστίασης (διαβάζει τη στήλη category = 'accommodation')
            Stat::make('Επιχειρήσεις Διαμονής', Business::where('category', 'accommodation')->count())
                ->description('Διαμονή')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning'),

            // 3. Επιχειρήσεις στο Κέντρο (διαβάζει τη στήλη area = 'center')
            Stat::make('Επιχειρήσεις στο Κέντρο', Business::where('area', 'center')->count())
                ->description('Μαγαζιά στο κέντρο του Βόλου')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),
            // 4. Επιχειρήσεις στο Κέντρο (διαβάζει τη στήλη area = 'agria')
            Stat::make('Επιχειρήσεις στην Αγριά', Business::where('area', 'agria')->count())
                ->description('Μαγαζιά στην Αγριά')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),

            // 5. Επιχειρήσεις στο Κέντρο (διαβάζει τη στήλη area = 'pilion')
            Stat::make('Επιχειρήσεις στο Πήλιο', Business::where('area', 'pilion')->count())
                ->description('Μαγαζιά στο Πήλιο')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),

            // 6. Επιχειρήσεις στο Κέντρο (διαβάζει τη στήλη area = 'neaionia')
            Stat::make('Επιχειρήσεις στην Νέα Ιωνία', Business::where('area', 'neaionia')->count())
                ->description('Μαγαζιά στην Νέα Ιωνία')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),

            // 7. Επιχειρήσεις στο Κέντρο (διαβάζει τη στήλη area = 'neadimitriada')
            Stat::make('Επιχειρήσεις στην Νέα Δημητριάδα', Business::where('area', 'eadimitriada')->count())
                ->description('Μαγαζιά στην Νέα Δημητριάδα')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),

            // 8. Επιχειρήσεις στο Κέντρο (διαβάζει τη στήλη area = 'alykes')
            Stat::make('Επιχειρήσεις στις Αλυκές', Business::where('area', 'alykes')->count())
                ->description('Μαγαζιά στις Αλυκές')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('success'),

            // 9. Συνολικές Κριτικές (από τον πίνακα reviews)
            Stat::make('Συνολικές Κριτικές', Review::count())
                ->description('Αξιολογήσεις από χρήστες')
                ->descriptionIcon('heroicon-m-star')
                ->color('gray'),

            // // 10. Αποθηκευμένα Αγαπημένα (από τον πίνακα favorites)
            // Stat::make('Αποθηκευμένα Αγαπημένα', Favorite::count())
            //     ->description('Πόσες φορές οι χρήστες πάτησαν like')
            //     ->descriptionIcon('heroicon-m-heart')
            //     ->color('danger'),
                    ];
    }
}
