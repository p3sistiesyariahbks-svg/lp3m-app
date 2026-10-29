<?php

namespace App\Filament\Resources\GalleryCategories\Schemas;

use App\Enums\TaxonomyType;
use App\Models\Taxonomy;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class GalleryCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Category')
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->string()
                            ->minLength(3)
                            ->maxLength(100)
                            ->label('Name')
                            ->autofocus(),
                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->relationship(
                                'parent',
                                'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('type', TaxonomyType::Gallery),
                            )
                            ->getOptionLabelFromRecordUsing(fn (Taxonomy $record): string => str_repeat('—', $record->depth)." {$record->name}")
                            ->native(false)
                            ->preload(),
                        Textarea::make('description')
                            ->string()
                            ->minLength(3)
                            ->maxLength(1024)
                            ->label('Description')
                            ->autosize(),
                        TextInput::make('slug')
                            ->required()
                            ->alphaDash()
                            ->minLength(3)
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->validationAttribute('url slug')
                            ->label('URL Slug')
                            ->prefix('/category/')
                            ->disabled()
                            ->visibleOn('edit')
                            ->belowContent('Auto-generated from name'),
                    ]),
            ]);
    }
}
