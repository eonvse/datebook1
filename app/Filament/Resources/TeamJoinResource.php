<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamJoinResource\Pages;
use App\Filament\Resources\TeamJoinResource\RelationManagers;
use App\Models\TeamJoin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamJoinResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = TeamJoin::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    protected static bool $isScopedToTenant = false;

    public static function getModelLabel(): string
    {
        return __('TeamJoin');
    }

    public static function getPluralModelLabel(): string
    {
        return __('TeamsJoin');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('management_team');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name'),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->required(),
                Forms\Components\TextInput::make('note')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team.name')
                    ->translateLabel()
                    ->searchable()
                    ,
                Tables\Columns\TextColumn::make('user.name')
                    ->translateLabel()
                    ->searchable()
                    ,
                Tables\Columns\TextColumn::make('note')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('last_status.status.description')
                    ->translateLabel()
                    ->badge()
                    ,
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultGroup('team.name')
            ->groups([
                Group::make('team.name')
                    ->label(__("Team"))
                    ->collapsible(),
                Group::make('user.name')
                    ->label(__("User"))
                    ->collapsible(),
            ])
            ->filters([
                //
            ])
            ->actions([
                /*Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton(),*/
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
            'index' => Pages\ManageTeamJoins::route('/'),
        ];
    }
}
