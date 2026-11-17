<?php

namespace App\Filament\Admin\Resources\NewsCategories;

use App\Enums\TaxonomyType;
use App\Filament\Admin\Resources\NewsCategories\Pages\CreateNewsCategory;
use App\Filament\Admin\Resources\NewsCategories\Pages\EditNewsCategory;
use App\Filament\Admin\Resources\NewsCategories\Pages\ListNewsCategories;
use App\Filament\Admin\Resources\NewsCategories\Schemas\NewsCategoryForm;
use App\Filament\Admin\Resources\NewsCategories\Tables\NewsCategoriesTable;
use App\Models\Taxonomy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NewsCategoryResource extends Resource
{
    protected static ?string $model = Taxonomy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = '/news/categories';

    protected static ?string $modelLabel = 'Category';

    public static function form(Schema $schema): Schema
    {
        return NewsCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsCategoriesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', TaxonomyType::News);
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
            'index' => ListNewsCategories::route('/'),
            'create' => CreateNewsCategory::route('/create'),
            'edit' => EditNewsCategory::route('/{record}/edit'),
        ];
    }
}
