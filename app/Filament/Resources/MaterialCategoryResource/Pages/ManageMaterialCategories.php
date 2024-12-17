<?php

namespace App\Filament\Resources\MaterialCategoryResource\Pages;

use App\Filament\Resources\MaterialCategoryResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class ManageMaterialCategories extends ManageRecords
{
    protected static string $resource = MaterialCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle(fn (Model $record): string =>__('Created category'). ": ".$record->name)
                ->modalWidth(MaxWidth::Large)
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['slug'] = Str::slug($data['name']);
                    $data['user_id'] = Auth::id();
                    $data['team_id'] = Filament::getTenant()->id;
                    return $data;
                }),
        ];
    }
}
