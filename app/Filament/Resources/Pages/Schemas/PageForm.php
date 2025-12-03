<?php

namespace App\Filament\Resources\Pages\Schemas;

use Closure;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->state([
                'is_published' => false, 
            ])
            ->components([
                Section::make('main details')
                    ->description('Basic page information')
                    ->schema([
                        TextInput::make('title')
                            ->label('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state) { 
                                if (empty($get('slug'))) {
                                $set('slug', \Illuminate\Support\Str::slug($state));
                                }
                            }),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->reactive(fn (Get $get): bool => filled($get('slug')))
                            ->helperText('The slug is automatically generated from the title and updated only if empty (on blur).'),
                    ])
                    ->columns(2),

                Section::make('content')
                    ->description('Manage page content blocks')
                    ->schema([
                        Builder::make('content')
                            ->label('Content Blocks')
                            ->blocks([
                                Builder\Block::make('text')
                                    ->label('Text')
                                    ->schema([
                                        RichEditor::make('body')
                                            ->label('Body of the Text')
                                            ->required(),
                                    ]),

                                Builder\Block::make('heading')
                                    ->label('Header')
                                    ->schema([
                                        Select::make('level')
                                            ->label('Level')
                                            ->options([
                                                'h1' => 'H1',
                                                'h2' => 'H2',
                                                'h3' => 'H3',
                                                'h4' => 'H4',
                                            ])
                                            ->default('h2')
                                            ->required(),

                                        TextInput::make('text')
                                            ->label('Header Text')
                                            ->required()
                                            ->maxLength(255),
                                    ]),

                                Builder\Block::make('image')
                                    ->label('Image')
                                    ->schema([
                                        FileUpload::make('url')
                                            ->label('Upload Image')
                                            ->image()
                                            ->required()
                                            ->disk('public') 
                                            ->directory('page-images'),

                                        TextInput::make('alt')
                                            ->label('Alternative Text (ALT)')
                                            ->maxLength(255),

                                        TextInput::make('caption')
                                            ->label('Caption')
                                            ->maxLength(255),
                                    ]),
                            ])
                            ->collapsible(),
                    ]),

                Section::make('Publication')
                    ->description('Publication status')
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),
                    ])
                    ->columns(1),
            ]);
    }
}