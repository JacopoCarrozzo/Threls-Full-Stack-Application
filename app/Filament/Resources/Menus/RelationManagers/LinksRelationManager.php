<?php

namespace App\Filament\Resources\Menus\RelationManagers;

use App\Filament\Resources\Pages\PageResource; // Necessario per il link alla pagina
use App\Models\Page; // Necessario per popolare la Select
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema; 
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LinksRelationManager extends RelationManager
{
    protected static string $relationship = 'links';

    public function form(Schema $schema): Schema
    {
        return $schema 
            ->components([
                // 1. Link Label (Text displayed)
                TextInput::make('label')
                    ->label('Link Text')
                    ->required()
                    ->maxLength(255),

                // 2. Page Selection (Relationship)
                Select::make('page_id')
                    ->label('Internal Landing Page')
                    ->options(Page::all()->pluck('title', 'id')) 
                    ->nullable()
                    ->helperText('Please select an internal page or provide an external URL. **Note:** The external URL takes precedence.'),

                // 3. External URL (Alternative)
                TextInput::make('url')
                    ->label('External URL')
                    ->url() 
                    ->maxLength(255)
                    ->nullable(),
                    
                // 4. Order (Required for Drag & Drop sorting)
                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->hidden(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order') 
            ->defaultSort('order', 'asc')
            ->columns([
                // 1. Label Column
                TextColumn::make('label')
                    ->sortable()
                    ->searchable(),

                // 2. Page Column (shows the title of the linked page)
                TextColumn::make('page.title')
                    ->label('Internal Page')
                    ->url(fn ($record) => $record->page_id ? PageResource::getUrl('edit', ['record' => $record->page_id]) : null)
                    ->default('—'),
                    
                // 3. External URL Column
                TextColumn::make('url')
                    ->label('External URL')
                    ->default('—')
                    ->url(fn ($record) => $record->url), 

                // 4. Order Column
                TextColumn::make('order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->filters([
                // Filter for Soft Deletes
                TrashedFilter::make(), 
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(), 
                ForceDeleteAction::make(), 
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    // Method to handle queries: We also show soft-deleted elements
    protected function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}