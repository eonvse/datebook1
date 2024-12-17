<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Filament\Resources\TeamResource\RelationManagers;
use App\Models\Team;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class TeamResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static bool $isScopedToTenant = false;

    public static function getModelLabel(): string
    {
        return __('Team');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Teams');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('management_team');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Team Name'))
                    ->required()
                    ->minLength(4)
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->translateLabel(),
                Forms\Components\Textarea::make('info')
                    ->label(__('Team Info'))
                    ->columnSpanFull(),
                Forms\Components\Select::make('users')
                    ->translateLabel()
                    ->multiple()
                    ->preload()
                    ->relationship(
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->getOptionLabelsUsing(fn (array $values): array => User::orderBy('name','asc')->pluck('name', 'id')->toArray())
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Team Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('info')
                    ->label(__('Team Info'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('users.name')
                    ->translateLabel()
                    //->listWithLineBreaks()
                    //->expandableLimitedList()
                    //->bulleted()
                    //->limitList(3)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('users')
                    ->translateLabel()
                    ->relationship('users', 'name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelsUsing(fn (array $values): array => User::orderBy('name','asc')->pluck('name', 'id')->toArray())
                    //->multiple()
                    ,
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(1)
            ->filtersTriggerAction(
                fn (Tables\Actions\Action $action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Saved team'). ": ".$record->name)
                    ->modalWidth(MaxWidth::Large)
                    ->modalIconColor('info')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['slug'] = Str::slug($data['name']);
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Team deleted'). ": ".$record->name),
            ])
            ->bulkActions([

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTeams::route('/'),
        ];
    }
}
