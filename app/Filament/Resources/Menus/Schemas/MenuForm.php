<?php

namespace App\Filament\Resources\Menus\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Menu Details')
                    ->description('Create new menu start give a nice name')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(1),
            ]);
    }
}