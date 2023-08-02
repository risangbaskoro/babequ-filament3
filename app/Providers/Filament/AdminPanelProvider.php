<?php

namespace App\Providers\Filament;

use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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

class AdminPanelProvider extends PanelProvider
{
    protected static string $id = 'admin';

    protected static string $path = 'admin';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id(static::$id)
            ->path(static::$path)
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->colors($this->getColors())
            ->resources($this->getResources())
            ->pages($this->getPages())
            ->widgets($this->getWidgets())
            ->middleware($this->getMiddleware())
            ->authMiddleware($this->getAuthMiddleware())
            ->topNavigation();
    }

    protected function getColors(): array
    {
        return [
            'primary' => Color::Blue,
        ];
    }

    protected function getResources(): array
    {
        return [
            ProductResource::class,
            CategoryResource::class,
            UserResource::class,
        ];
    }

    protected function getPages(): array
    {
        return [
            Pages\Dashboard::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            Widgets\AccountWidget::class,
        ];
    }

    protected function getMiddleware(): array
    {
        return [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ];
    }

    public function getAuthMiddleware(): array
    {
        return [
            Authenticate::class,
        ];
    }
}
