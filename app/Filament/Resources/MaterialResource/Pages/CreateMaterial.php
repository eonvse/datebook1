<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;
use Illuminate\Support\Str;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('Created material').": ".$this->getRecord()->name;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Filament::auth()->id();
        $data['team_id'] = Filament::getTenant()->id;
        $data['slug'] = Str::slug($data['name']);
        return $data;
    }

}
