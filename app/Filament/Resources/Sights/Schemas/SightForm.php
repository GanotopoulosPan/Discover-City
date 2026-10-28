<?php

namespace App\Filament\Resources\Sights\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;

class SightForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Πληροφορίες Αξιοθέατου')
                    ->components([
                        TextInput::make('name')
                            ->label('Όνομα Αξιοθέατου')
                            ->required(),

                        Textarea::make('mini_description') 
                            ->label('Σύντομη Περιγραφή')
                            ->maxLength(75)
                            ->columnSpanFull(),

                        FileUpload::make('photo_profile')
                            ->label('Εικόνα Προφίλ')
                            // ->image()
                            ->disk('public')
                            ->directory('sights/profiles'),

                        FileUpload::make('photos') 
                            ->label('Άλλες Εικόνες (Gallery)')
                            // ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('sights')
                            ->columnSpanFull(),

                        RichEditor::make('description')
                            ->label('Περιγραφή')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}