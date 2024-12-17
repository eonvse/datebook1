<?php

namespace App\Filament\Clusters\Admin\Resources\RoleResource\Pages;

use App\Filament\Clusters\Admin\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;

class ManageRoles extends ManageRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle(fn (Model $record): string =>__('Created role'). ": ".$record->name)
                ->modalWidth(MaxWidth::Large)
                ->createAnother(false)
                ->modalIcon('heroicon-o-identification')
                ->modalIconColor('gray'),
        ];
    }

}
