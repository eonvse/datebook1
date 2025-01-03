<?php

namespace App\Filament\Resources;

use App\Events\Team\JoinApprove;
use App\Events\Team\JoinCancel;

use App\Filament\Resources\TeamJoinResource\Pages;
use App\Filament\Resources\TeamJoinResource\RelationManagers;

use App\Models\Status;
use App\Models\TeamJoin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

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
                    ->color(fn (Model $record): string => match ($record->last_status->status->name) {
                        'new' => 'info',
                        'approved' => 'success',
                        'cancelled' => 'danger',
                    })
                    ,
                Tables\Columns\TextColumn::make('last_status.author.name')
                    ->label(__('Executor'))
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
                Filter::make('status')
                    ->form([Forms\Components\Select::make('status_id')
                                ->label(__("Status"))
                                ->options(function() {
                                    return Status::where('model','=',TeamJoin::class)->pluck('description','id')->toArray();
                                })
                            ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                                ->when(
                                    $data['status_id'],
                                    fn (Builder $query, $status_id): Builder => $query->
                                                    whereRelation('last_status', 'status_id', $status_id)
                                );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['status_id']) {
                            return null;
                        }

                        return __('Status').': '. Status::find($data['status_id'])->description;
                    }),

                ], layout: FiltersLayout::AboveContentCollapsible)
            ->persistFiltersInSession()
            //->deferFilters()
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label(__('Filter')),
                )
            ->filtersFormColumns(2)

            ->actions([
                Tables\Actions\Action::make('approve')
                    ->translateLabel()
                    ->icon('heroicon-o-check-circle')
                    //->iconButton()
                    ->color('success')
                    ->action(fn($record) => JoinApprove::dispatch($record,Auth::user()))
                    ->visible(fn($record) => $record->last_status->status->name === 'new'),
                Tables\Actions\Action::make('cancel')
                    ->translateLabel()
                    ->icon('heroicon-o-x-circle')
                    //->iconButton()
                    ->color('warning')
                    ->action(fn($record) => JoinCancel::dispatch($record,Auth::user()))
                    ->visible(fn($record) => $record->last_status->status->name === 'new'),

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
