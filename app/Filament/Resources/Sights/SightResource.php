<?php

namespace App\Filament\Resources\Sights;

use App\Filament\Resources\Sights\Pages\CreateSight;
use App\Filament\Resources\Sights\Pages\EditSight;
use App\Filament\Resources\Sights\Pages\ListSights;
use App\Filament\Resources\Sights\Schemas\SightForm;
use App\Filament\Resources\Sights\Tables\SightsTable;
use App\Models\Sight;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SightResource extends Resource
{
    protected static ?string $model = Sight::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationLabel = 'Αξιοθέατα';
    protected static ?string $modelLabel = 'Αξιοθέατο';
    protected static ?string $pluralModelLabel = 'Αξιοθέατα';

    public static function form(Schema $schema): Schema
    {
        // Καλεί το εξωτερικό αρχείο της φόρμας
        return SightForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // Καλεί το εξωτερικό αρχείο του πίνακα
        return SightsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSights::route('/'),
            'create' => CreateSight::route('/create'),
            'edit' => EditSight::route('/{record}/edit'),
        ];
    }
    
}