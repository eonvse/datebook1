<?php

namespace App\Filament\Clusters\Admin\Resources\StatusResource\Pages;

use App\Filament\Clusters\Admin\Resources\StatusResource;
use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageStatuses extends ManageRecords
{
    protected static string $resource = StatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle(fn (Model $record): string =>__('Created status'). ": ".$record->name)
                ->modalWidth(MaxWidth::Large)
                ->createAnother(false)
                ->modalIcon('heroicon-o-ellipsis-horizontal-circle')
                ->modalIconColor('gray'),
        ];
    }
}
