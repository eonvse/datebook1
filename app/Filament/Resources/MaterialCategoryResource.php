<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialCategoryResource\Pages;
use App\Filament\Resources\MaterialCategoryResource\RelationManagers;
use App\Models\MaterialCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialCategoryResource extends Resource
{
    protected static ?string $model = MaterialCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark';

    public static function getModelLabel(): string
    {
        return __('Material category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Material categories');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Materials');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Category name'))
                    ->required()
                    ->minLength(4)
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->translateLabel()
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('description')
                    ->translateLabel()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Category name'))
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn (Model $record): string => "{$record->description}"),
                Tables\Columns\TextColumn::make('description')
                    ->wrap()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('order')
                    ->translateLabel()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('materials_count')->counts('materials')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('user.name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->paginatedWhileReordering()
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Saved category'). ": ".$record->name)
                    ->modalWidth(MaxWidth::Large)
                    ->modalIconColor('info'),

                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Category deleted'). ": ".$record->name),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMaterialCategories::route('/'),
        ];
    }
}
