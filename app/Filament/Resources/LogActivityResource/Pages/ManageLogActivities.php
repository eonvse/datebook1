<?php

namespace App\Filament\Resources\LogActivityResource\Pages;

use App\Filament\Resources\LogActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLogActivities extends ManageRecords
{
    protected static string $resource = LogActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
