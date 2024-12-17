<?php

namespace App\Providers\Filament;

use App\Filament\AvatarProviders\AvatarsProvider;
use App\Filament\Resources\MaterialCategoryResource\Widgets\CategoryesTable;
use App\Filament\Widgets\ControlLink;
use App\Models\Team;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ControlPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('control')
            ->path('control')
            ->login()
            //->profile()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'info' => Color::Sky,
                'primary' => Color::Blue,
                'success' => Color::Teal,
                'warning' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
                ControlLink::class,
                CategoryesTable::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->tenant(Team::class, slugAttribute: 'slug')
            ->tenantMenuItems([
                //TODO: Сделать переход на страницу заявки вступления в группу
                MenuItem::make()
                    ->label(__('Joining the team'))
                    ->url(fn (): string => '#'/*route('team_join')*/)
                    ->icon('heroicon-m-arrow-right-end-on-rectangle'),
            ])
            ->profile(isSimple: false)
            ->defaultAvatarProvider(AvatarsProvider::class)
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(__('management_team'))
                    ->icon('heroicon-o-user-group'),
                NavigationGroup::make()
                    ->label(__('Materials'))
                    ->icon('heroicon-o-newspaper'),
                NavigationGroup::make()
                    ->label(__('management'))
                    ->icon('heroicon-o-wrench'),

            ])
            ;
    }
}
