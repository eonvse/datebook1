<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogActivityResource\Pages;
use App\Models\LogActivity;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class LogActivityResource extends Resource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $model = LogActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static bool $isScopedToTenant = false;

    public static function getPluralModelLabel(): string
    {
        return __('Log Activities');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('management');
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model_name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_name')
                    ->label(__('Model item name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_id')
                    ->label(__('Model item ID'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('operation')
                    ->translateLabel()
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'updated' => 'warning',
                        'created' => 'success',
                        'deleted' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('user_name')
                    ->label(__('User'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->translateLabel()
                    ->numeric(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('method')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ip')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agent')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('created_at','desc')
            ->striped()
            ->filters([
                SelectFilter::make('model')
                    ->label(__('Model name'))
                    ->options(function() {
                        return LogActivity::all()->unique('model')->pluck('model_name','model')->toArray();
                    }),
                SelectFilter::make('operation')
                    ->translateLabel()
                    ->options([
                        'updated' => 'updated',
                        'created' => 'created',
                        'deleted' => 'deleted',
                    ]),
                SelectFilter::make('user_id')
                    ->label(__('User'))
                    ->options(function(){
                        return LogActivity::all()->unique('user_id')->pluck('user_name','user_id')->toArray();
                    }),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->translateLabel(),
                        Forms\Components\DatePicker::make('created_until')
                            ->translateLabel(),
                    ])
                    ->columns(['sm' => 2])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['created_from'] && ! $data['created_until'] ) {
                            return null;
                        }
                        $msg = '';
                        if ($data['created_from']) $msg.= __('Created from'). ': ' . Carbon::parse($data['created_from'])->format('d.m.Y').' ';
                        if ($data['created_until']) $msg.= __('Created until'). ': ' . Carbon::parse($data['created_until'])->format('d.m.Y');

                        return $msg;
                    }),

                ], layout: FiltersLayout::AboveContentCollapsible)
            ->persistFiltersInSession()
            //->deferFilters()
            ->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->filtersFormColumns(3)
            ->actions([
                //
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
            'index' => Pages\ManageLogActivities::route('/'),
        ];
    }
}
