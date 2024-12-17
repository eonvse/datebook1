<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Admin extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static bool $isScopedToTenant = false;

    public static function getNavigationGroup(): ?string
    {
        return __('management');
    }
    public static function getClusterBreadcrumb(): string
    {
        return __('Admin');
    }

    public static function getNavigationLabel(): string
    {
        return __('Admin');
    }

}
