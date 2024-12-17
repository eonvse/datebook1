<?php

namespace App\Filament\Clusters\Admin\Resources\UserResource\Pages;

use App\Filament\Clusters\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->successNotificationTitle(fn (Model $record): string =>__('Created user'). ": ".$record->name)
                ->modalWidth(MaxWidth::Large)
                ->createAnother(false)
                ->modalIcon('heroicon-o-user-plus')
                ->modalIconColor('gray'),
        ];
    }
}
