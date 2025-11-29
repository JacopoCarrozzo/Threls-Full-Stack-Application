<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Filament\Resources\Menus\RelationManagers\LinksRelationManager;
use App\Filament\Resources\Menus\Schemas\MenuForm;
use App\Filament\Resources\Menus\Tables\MenusTable;
use App\Models\Menu;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
       //Role: Defines the fields that appear on the Menu creation and editing pages. 
       //Delegates responsibility to the external class MenuForm::configure() (found in Schemas/MenuForm.php)
       return MenuForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        //Role: Defines the columns and features (search, filters) that appear on the list page (/admin/menus).
        //Similar to form(), it delegates the definition of columns to the external class MenusTable::configure() (found in Tables/MenusTable.php)
        return MenusTable::configure($table);
    }

    
    public static function getRelations(): array
    {
        return [
            LinksRelationManager::class, 
        ];
    }

    public static function getPages(): array
    {
        //Role: Map page names (index, create, edit) to specific URLs
        //This method defines the navigation structure within the resource linking the UI classes (ListMenus.php, CreateMenu.php, EditMenu.php) to the URL paths you see in the browser.
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}