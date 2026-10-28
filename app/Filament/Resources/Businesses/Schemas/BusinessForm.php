<?php

namespace App\Filament\Resources\Businesses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select; 
use Filament\Schemas\Schema;

class BusinessForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('address')
                    ->required(),
                 TextInput::make('tk')
                    ->label('Τ.Κ.')
                    ->required()
                    ->maxLength(5),   
                Select::make('area')
                    ->label('Περιοχή')
                    ->options([
                        'center' => 'Κέντρο / Λιμάνι',
                        'pilion' => 'Πήλιο / Βουνό',
                        'neaionia' => 'Νέα Ιωνία',
                        'agria' => 'Αγριά',
                        'alykes' => 'Αλυκές',
                        'neadimitriada' => 'Νέα Δημητριάδα',
                    ])
                    ->required()
                    ->searchable(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('website')
                    ->url()
                    ->default(null),
                TextInput::make('category')
                    ->required(),
                TextInput::make('type')
                    ->default(null),
                Textarea::make('amenities')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('duration')
                    ->default(null),
                TextInput::make('difficulty')
                    ->default(null),
                TextInput::make('schedule')
                    ->default(null),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('photos')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
