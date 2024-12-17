<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Filament\Clusters\Admin;
use App\Filament\Clusters\Admin\Resources\StatusResource\Pages;
use App\Filament\Clusters\Admin\Resources\StatusResource\RelationManagers;
use App\Models\Status;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatusResource extends Resource
{
    protected static ?string $model = Status::class;

    protected static ?string $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';

    protected static ?string $cluster = Admin::class;

    protected static bool $isScopedToTenant = false;

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return __('Status');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Statuses');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('model')
                    ->translateLabel()
                    ->required()
                    ->options(function(){
                        $models = get_app_models_list();
                        $data = array_combine(
                            array_map(function($v){ return $v; }, $models),
                            array_map(function($v){ return str_replace('App\\Models\\','',$v); }, $models)
                        );
                        return $data;
                    })
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('name')
                    ->translateLabel()
                    ->required()
                    ->minLength(3)
                    ->maxLength(255)
                    ->disabledOn('edit'),
                Forms\Components\TextInput::make('description')
                    ->translateLabel()
                    ->minLength(4)
                    ->maxLength(255),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->translateLabel()
                    ->searchable(),
            ])
            ->defaultGroup('model')
            ->groups([
                Group::make('model')
                    ->label(__("Model"))
                    ->collapsible(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Saved status'). ": ".$record->name)
                    ->modalWidth(MaxWidth::Large)
                    ->modalIcon('heroicon-o-ellipsis-horizontal-circle'),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Status deleted'). ": ".$record->name),
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
            'index' => Pages\ManageStatuses::route('/'),
        ];
    }
}
