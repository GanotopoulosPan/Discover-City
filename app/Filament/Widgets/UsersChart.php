<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User; 

class UsersChart extends ChartWidget
{
    protected ?string $heading = 'Ρυθμός Νέων Εγγραφών (Τρέχον Έτος)';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        // Μετράμε τις εγγραφές για κάθε μήνα ξεχωριστά
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            $data[] = User::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Νέοι Χρήστες',
                    'data' => $data,
                    'borderColor' => '#3b82f6', // Μπλε χρώμα για τη γραμμή
                ],
            ],
            'labels' => ['Ιαν', 'Φεβ', 'Μαρ', 'Απρ', 'Μάι', 'Ιούν', 'Ιούλ', 'Αύγ', 'Σεπ', 'Οκτ', 'Νοέ', 'Δεκ'],
        ];
    }
}