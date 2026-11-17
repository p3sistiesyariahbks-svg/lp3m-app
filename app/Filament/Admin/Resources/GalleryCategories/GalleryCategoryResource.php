<?php

namespace App\Filament\Admin\Resources\GalleryCategories;

use App\Enums\TaxonomyType;
use App\Filament\Admin\Resources\GalleryCategories\Pages\CreateGalleryCategory;
use App\Filament\Admin\Resources\GalleryCategories\Pages\EditGalleryCategory;
use App\Filament\Admin\Resources\GalleryCategories\Pages\ListGalleryCategories;
use App\Filament\Admin\Resources\GalleryCategories\Schemas\GalleryCategoryForm;
use App\Filament\Admin\Resources\GalleryCategories\Tables\GalleryCategoriesTable;
use App\Models\Taxonomy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GalleryCategoryResource extends Resource
{
    protected static ?string $model = Taxonomy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'gallery/categories';

    protected static ?string $modelLabel = 'Category';

    public static function form(Schema $schema): Schema
    {
        return GalleryCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryCategoriesTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', TaxonomyType::Gallery);
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
            'index' => ListGalleryCategories::route('/'),
            'create' => CreateGalleryCategory::route('/create'),
            'edit' => EditGalleryCategory::route('/{record}/edit'),
        ];
    }
}
