<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Review;

// class RecentReviews extends BaseWidget
// {
//     // Να πιάνει όλο το πλάτος
//     protected int | string | array $columnSpan = 'full'; 

//     protected static ?string $heading = 'Πρόσφατες Αξιολογήσεις Χρηστών';

//     public function table(Table $table): Table
//     {
//         return $table
//             ->query(
//                 Review::query()->latest()->limit(5)
//             )
//             ->paginated(false) 
//             ->columns([
//                 TextColumn::make('user.name')
//                     ->label('Χρήστης')
//                     ->weight('bold'),

//                 TextColumn::make('comment')
//                     ->label('Σχόλιο / Κριτική')
//                     ->wrap(), 

//                 TextColumn::make('created_at')
//                     ->label('Ημερομηνία')
//                     ->dateTime('d/m/Y H:i')
//                     ->color('gray'),
//             ]);
//     }
// }