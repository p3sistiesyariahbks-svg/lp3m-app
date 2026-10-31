<?php

namespace App\Filament\Resources\GalleryVideos;

use App\Enums\GalleryType;
use App\Filament\Resources\GalleryVideos\Pages\CreateGalleryVideo;
use App\Filament\Resources\GalleryVideos\Pages\EditGalleryVideo;
use App\Filament\Resources\GalleryVideos\Pages\ListGalleryVideos;
use App\Filament\Resources\GalleryVideos\Schemas\GalleryVideoForm;
use App\Filament\Resources\GalleryVideos\Tables\GalleryVideosTable;
use App\Filament\Resources\GalleryVideos\Widgets\GalleryVideoStatsOverview;
use App\Models\Gallery;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GalleryVideoResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'gallery/videos';

    protected static ?string $modelLabel = 'Video';

    public static function form(Schema $schema): Schema
    {
        return GalleryVideoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryVideosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', GalleryType::Videos);
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
            GalleryVideoStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleryVideos::route('/'),
            'create' => CreateGalleryVideo::route('/create'),
            'edit' => EditGalleryVideo::route('/{record}/edit'),
        ];
    }
}
