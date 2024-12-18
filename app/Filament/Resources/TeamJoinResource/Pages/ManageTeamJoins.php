<?php

namespace App\Filament\Resources\TeamJoinResource\Pages;

use App\Filament\Resources\TeamJoinResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTeamJoins extends ManageRecords
{
    protected static string $resource = TeamJoinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
