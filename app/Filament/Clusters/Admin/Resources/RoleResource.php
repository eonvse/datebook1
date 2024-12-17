<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Filament\Clusters\Admin;
use App\Filament\Clusters\Admin\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Admin::class;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('Role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Roles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Role name'))
                    ->required()
                    ->minLength(4)
                    ->maxLength(255)
                    ->disabledOn('edit'),
                Forms\Components\Textarea::make('description')
                    ->translateLabel()
                    ->required()
                    ->autosize(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Role name'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->translateLabel()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Saved role'). ": ".$record->name)
                    ->modalWidth(MaxWidth::Large)
                    ->modalIcon('heroicon-o-identification')
                    ->modalIconColor('info'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Role deleted'). ": ".$record->name),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }
}
