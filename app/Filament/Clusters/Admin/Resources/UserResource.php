<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Filament\Clusters\Admin;
use App\Filament\Clusters\Admin\Resources\UserResource\Pages;
use App\Filament\Clusters\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static bool $isScopedToTenant = false;

    protected static ?string $cluster = Admin::class;

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }


    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label(__('User name'))
                ->required()
                ->minLength(4)
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->translateLabel()
                ->email()
                ->required()
                ->maxLength(255)
                ->disabledOn('edit'),
            Forms\Components\TextInput::make('password')
                ->translateLabel()
                ->password()
                ->required()
                ->maxLength(255)
                ->hiddenOn('edit'),
            Forms\Components\Select::make('roles')
                ->translateLabel()
                ->multiple()
                ->preload()
                ->relationship(
                    titleAttribute: 'name',
                    modifyQueryUsing: fn (Builder $query) => $query->where('name','<>','Root'),
                ),
        ])->columns(1);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('User name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->translateLabel()
                    ->icon('heroicon-m-envelope')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->translateLabel()
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('roles')
                ->translateLabel()
                ->relationship('roles', 'name')
                //->searchable()
                ->preload()
                //->getOptionLabelsUsing(fn (array $values): array => ZniInitiator::orderBy('name','asc')->pluck('name', 'id')->toArray())
                ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Saved user'). ": ".$record->name)
                    ->modalWidth(MaxWidth::Large)
                    ->modalIcon('heroicon-o-user')
                    ->modalIconColor('info'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('User deleted'). ": ".$record->name),

            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
