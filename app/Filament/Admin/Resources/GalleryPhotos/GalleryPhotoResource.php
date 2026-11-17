<?php

namespace App\Filament\Admin\Resources\GalleryPhotos;

use App\Enums\GalleryType;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\CreateGalleryPhoto;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\EditGalleryPhoto;
use App\Filament\Admin\Resources\GalleryPhotos\Pages\ListGalleryPhotos;
use App\Filament\Admin\Resources\GalleryPhotos\Schemas\GalleryPhotoForm;
use App\Filament\Admin\Resources\GalleryPhotos\Tables\GalleryPhotosTable;
use App\Filament\Admin\Resources\GalleryPhotos\Widgets\GalleryPhotoStatsOverview;
use App\Models\Gallery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GalleryPhotoResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'gallery/photos';

    protected static ?string $modelLabel = 'Photo';

    public static function form(Schema $schema): Schema
    {
        return GalleryPhotoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryPhotosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', GalleryType::Photos);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            GalleryPhotoStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleryPhotos::route('/'),
            'create' => CreateGalleryPhoto::route('/create'),
            'edit' => EditGalleryPhoto::route('/{record}/edit'),
        ];
    }
}
