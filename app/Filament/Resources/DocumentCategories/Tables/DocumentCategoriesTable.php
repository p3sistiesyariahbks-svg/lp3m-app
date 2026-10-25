<?php

namespace App\Filament\Resources\DocumentCategories\Tables;

use App\Models\Taxonomy;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Kisame76\FilamentTreeTable\ExpandableRows;

class DocumentCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return ExpandableRows::make()
            ->parentKey('parent_id')
            ->childrenRelationship('children')
            ->applyTo(
                $table
                    ->columns([
                        TextColumn::make('name')
                            ->label('Name')
                            ->description(fn (Taxonomy $record): string => $record->slug)
                            ->words(10)
                            ->searchable()
                            ->sortable()
                            ->grow(),
                        TextColumn::make('documents_count')
                            ->label('Documents')
                            ->counts('documents')
                            ->numeric()
                            ->default(0)
                            ->sortable()
                            ->alignEnd(),
                        TextColumn::make('created_at')
                            ->label('Created')
                            ->dateTime()
                            ->since()
                            ->isoDateTimeTooltip()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                        TextColumn::make('updated_at')
                            ->label('Updated')
                            ->dateTime()
                            ->since()
                            ->isoDateTimeTooltip()
                            ->sortable()
                            ->toggleable(isToggledHiddenByDefault: true),
                    ])
                    ->filters([
                        //
                    ])
                    ->recordActions([
                        ActionGroup::make([
                            EditAction::make()
                                ->color('gray')
                                ->hiddenLabel(),
                            DeleteAction::make()
                                ->color('gray')
                                ->hiddenLabel(),
                        ])
                            ->buttonGroup(),
                    ])
                    ->toolbarActions([
                        BulkActionGroup::make([
                            DeleteBulkAction::make(),
                        ]),
                    ])
            )
            ->queryStringIdentifier('document-categories');
    }
}
