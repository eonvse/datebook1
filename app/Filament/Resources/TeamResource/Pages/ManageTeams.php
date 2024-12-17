<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ManageTeams extends ManageRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle(fn (Model $record): string =>__('Created team'). ": ".$record->name)
                ->modalWidth(MaxWidth::Large)
                ->createAnother(false)
                ->mutateFormDataUsing(function (array $data): array {
                    $data['slug'] = Str::slug($data['name']);
                    return $data;
                }),
        ];
    }
}
