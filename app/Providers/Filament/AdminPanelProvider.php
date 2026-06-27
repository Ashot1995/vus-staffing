<?php

namespace App\Providers\Filament;

use App\Http\Middleware\IsAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
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
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
                IsAdmin::class,
            ])
            ->renderHook('panels::body.end', fn () => <<<'HTML'
<script>
(function () {
    function setupStickyScrollbar() {
        var container = document.querySelector('.fi-ta-content');
        if (!container || container.dataset.stickyScrollbar) return;
        container.dataset.stickyScrollbar = '1';

        var bar = document.createElement('div');
        bar.style.cssText = 'position:fixed;bottom:0;overflow-x:auto;overflow-y:hidden;z-index:9999;background:transparent;';
        var inner = document.createElement('div');
        bar.appendChild(inner);
        document.body.appendChild(bar);

        function sync() {
            var rect = container.getBoundingClientRect();
            var visible = rect.top < window.innerHeight && rect.bottom > 0;
            bar.style.display = visible && container.scrollWidth > container.clientWidth ? 'block' : 'none';
            bar.style.left = rect.left + 'px';
            bar.style.width = rect.width + 'px';
            inner.style.width = container.scrollWidth + 'px';
            inner.style.height = '1px';
        }

        var syncing = false;
        container.addEventListener('scroll', function () {
            if (syncing) return;
            syncing = true;
            bar.scrollLeft = container.scrollLeft;
            syncing = false;
        });
        bar.addEventListener('scroll', function () {
            if (syncing) return;
            syncing = true;
            container.scrollLeft = bar.scrollLeft;
            syncing = false;
        });

        window.addEventListener('scroll', sync, { passive: true });
        window.addEventListener('resize', sync);
        sync();
    }

    function init() {
        setupStickyScrollbar();
        document.addEventListener('livewire:navigated', setupStickyScrollbar);
        document.addEventListener('livewire:update', function () {
            setTimeout(setupStickyScrollbar, 100);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
HTML);
    }
}
