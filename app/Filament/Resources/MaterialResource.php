<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use App\Models\MaterialCategory;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function getModelLabel(): string
    {
        return __('Material');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Materials');
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
                    ->translateLabel()
                    ->required()
                    ->minLength(4)
                    ->maxLength(255),
                Forms\Components\TextInput::make('order')
                    ->translateLabel()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('annotation')
                    ->translateLabel()
                    ->maxLength(255),
                Forms\Components\Select::make('material_category_id')
                    ->label(__('Material category'))
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('team_id', Filament::getTenant()->id)->orderBy('order','asc')->orderBy('name','asc'),
                    ),
                Forms\Components\RichEditor::make('text')
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('attachments')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('Material category'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('order')
                    ->translateLabel()
                    ->rules(['required', 'numeric'])
                    //->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('annotation')
                    ->translateLabel()
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('files_count')->counts('files')
                    ->label(__('Count files')),
                Tables\Columns\TextColumn::make('user.name')
                    ->translateLabel()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->paginatedWhileReordering()
            ->filters([
                Filter::make('material_category_id')
                    ->form([
                        Forms\Components\Select::make('category_id')
                            ->label(__('Material category'))
                            ->options(function() {
                                $data = MaterialCategory::where('team_id', Filament::getTenant()->id)->pluck('name','id');
                                $data->prepend(__('No category'), -1);
                                return $data;
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['category_id'],
                                fn (Builder $query, $date): Builder => $date==-1 ?
                                                                        $query->whereNull('material_category_id') :
                                                                        $query->where('material_category_id',$date)
                                                                        ,
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['category_id']) {
                            return null;
                        }
                        $dataIndicator = MaterialCategory::where('id', $data['category_id'])->pluck('name')[0] ??  __('No category');
                        return __('Material category') .": ". $dataIndicator;
                    })
            ])
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->successNotificationTitle(fn (Model $record): string =>__('Material deleted'). ": ".$record->name),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\FilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'view' => Pages\ViewMaterial::route('/{record}'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
