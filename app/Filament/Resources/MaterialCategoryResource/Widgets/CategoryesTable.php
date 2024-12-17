<?php

namespace App\Filament\Resources\MaterialCategoryResource\Widgets;

use App\Models\Material;
use App\Filament\Resources\MaterialResource;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoryesTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Material::query()
                    ->select(
                        DB::raw('COALESCE(materials.material_category_id,-1) as category_id'), 
                        DB::raw('COALESCE(material_categories.name,"Без категории") as category_name'), 
                        DB::raw('count(materials.id) as materials_count'))
                    ->leftJoin('material_categories','material_categories.id','materials.material_category_id')
                    ->where('materials.team_id', Filament::getTenant()->id)
                    ->groupBy('materials.material_category_id')
                    ->orderBy('material_categories.order', 'asc')
                    ->orderBy('material_categories.name', 'asc')
                            
            )
            ->columns([
                Tables\Columns\TextColumn::make('category_name')
                    ->label(__('Material category'))
                    ->color(fn (string $state): string => match ($state) {
                        'Без категории' => 'danger',
                        default => 'default',
                    })
                    ->weight(FontWeight::Medium)
                    ,
                Tables\Columns\TextColumn::make('materials_count')
                    ->label(__('Materials count'))
                    ->size(TextColumn\TextColumnSize::Large)
                    ->weight(FontWeight::Bold)
                    ->alignCenter()
                    ,

            ])
            ->heading(__(':team categories list',['team' => Filament::getTenant()->name]))
            ->recordUrl(
                fn (Model $record): string => MaterialResource::getUrl('index').'?tableFilters[material_category_id][category_id]='.$record->category_id,
            );
    
            ;
    }

    public function getTableRecordKey(Model $record): string {
        return $record->category_id;
    }
}
