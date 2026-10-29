<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
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
            ->profile(EditProfile::class, isSimple: false)
            ->brandName('Absensi')
            ->favicon(asset('icon.png'))
            ->colors([
                'primary' => Color::Blue,
            ])
            ->maxContentWidth(Width::Full)
            ->sidebarFullyCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
                    <script>
                        if (!window.filamentChartJsGlobalPlugins) {
                            window.filamentChartJsGlobalPlugins = [];
                            window.filamentChartJsGlobalPlugins.push({
                                id: "chartValueLabels",
                                afterDatasetsDraw(chart) {
                                    const type = chart.config.type;
                                    if (type !== "bar" && type !== "line") {
                                        return;
                                    }
                                    const { ctx } = chart;
                                    ctx.save();
                                    const isDark = document.documentElement.classList.contains("dark");
                                    ctx.font = "bold 11px Inter, system-ui, -apple-system, sans-serif";
                                    ctx.textAlign = "center";
                                    ctx.textBaseline = "bottom";
                                    const haloColor = isDark ? "#09090b" : "#ffffff";
                                    const drawn = [];

                                    chart.data.datasets.forEach((dataset, datasetIndex) => {
                                        const meta = chart.getDatasetMeta(datasetIndex);
                                        if (meta && !meta.hidden) {
                                            meta.data.forEach((element, index) => {
                                                const value = dataset.data[index];
                                                if (value !== undefined && value !== null && value > 0) {
                                                    let x = element.x;
                                                    let y = element.y - (type === "line" ? 8 : 4);

                                                    for (const d of drawn) {
                                                        if (Math.abs(d.x - x) < 14 && Math.abs(d.y - y) < 14) {
                                                            y -= 13;
                                                        }
                                                    }

                                                    drawn.push({ x, y });

                                                    ctx.lineWidth = 3;
                                                    ctx.strokeStyle = haloColor;
                                                    ctx.strokeText(value, x, y);

                                                    ctx.fillStyle = dataset.borderColor || (isDark ? "#f1f5f9" : "#1e293b");
                                                    ctx.fillText(value, x, y);
                                                }
                                            });
                                        }
                                    });
                                    ctx.restore();
                                }
                            });
                        }
                    </script>
                    <style>
                        .fi-main,
                        .fi-page,
                        .fi-page-header-main-ctn,
                        .fi-page-main,
                        .fi-page-content,
                        .fi-sc-form,
                        .fi-section,
                        .fi-section-content-ctn,
                        .fi-section-content {
                            width: 100% !important;
                            max-width: 100% !important;
                        }

                        .fi-icon,
                        .fi-sidebar-item-icon,
                        .fi-section-header svg,
                        .fi-tabs svg,
                        .fi-btn svg,
                        [data-slot="icon"],
                        svg.w-3, svg.w-4, svg.w-5, svg.w-6 {
                            flex-shrink: 0 !important;
                            display: inline-block;
                            vertical-align: middle;
                        }

                        [wire\:loading]:not([wire\:loading\.remove]),
                        [wire\:loading\.delay]:not([wire\:loading\.remove]),
                        [wire\:loading\.delay\.default]:not([wire\:loading\.remove]),
                        .fi-loading-indicator:not([wire\:loading\.remove]) {
                            display: none;
                        }

                        svg.w-3, .w-3 { width: 0.75rem !important; height: 0.75rem !important; min-width: 0.75rem !important; max-width: 0.75rem !important; }
                        svg.w-4, .w-4 { width: 1rem !important; height: 1rem !important; min-width: 1rem !important; max-width: 1rem !important; }
                        svg.w-5, .w-5 { width: 1.25rem !important; height: 1.25rem !important; min-width: 1.25rem !important; max-width: 1.25rem !important; }
                        svg.w-6, .w-6 { width: 1.5rem !important; height: 1.5rem !important; min-width: 1.5rem !important; max-width: 1.5rem !important; }

                        .fi-icon.fi-size-sm,
                        .fi-size-sm > svg {
                            width: 1rem !important;
                            height: 1rem !important;
                            max-width: 1rem !important;
                            max-height: 1rem !important;
                        }

                        .fi-icon.fi-size-md,
                        .fi-size-md > svg,
                        .fi-tabs .fi-icon {
                            width: 1.25rem !important;
                            height: 1.25rem !important;
                            max-width: 1.25rem !important;
                            max-height: 1.25rem !important;
                        }

                        .fi-icon.fi-size-lg,
                        .fi-size-lg > svg {
                            width: 1.5rem !important;
                            height: 1.5rem !important;
                            max-width: 1.5rem !important;
                            max-height: 1.5rem !important;
                        }

                        .fi-topbar-start {
                            display: flex !important;
                            align-items: center !important;
                        }

                        .fi-topbar-start > a,
                        .fi-topbar-start > .fi-logo {
                            order: 1 !important;
                        }

                        .fi-topbar-start .fi-logo {
                            margin-inline-start: 0 !important;
                        }

                        .fi-topbar-start > .fi-topbar-collapse-sidebar-btn-ctn {
                            order: 2 !important;
                            margin-inline-start: 0.75rem !important;
                            margin-inline-end: 0 !important;
                        }

                        @media (min-width: 1024px) {
                            .fi-topbar > .fi-topbar-start {
                                order: 1 !important;
                            }

                            .fi-topbar > .fi-topbar-open-sidebar-btn {
                                order: 2 !important;
                                margin-inline-start: 0.75rem !important;
                                margin-inline-end: 1rem !important;
                            }

                            .fi-topbar > .fi-topbar-nav-groups {
                                order: 3 !important;
                            }

                            .fi-topbar > .fi-topbar-end {
                                order: 99 !important;
                            }

                            .fi-sidebar {
                                position: sticky !important;
                                top: var(--topbar-height, 4rem) !important;
                                height: calc(100dvh - var(--topbar-height, 4rem)) !important;
                                width: var(--sidebar-width) !important;
                                flex-shrink: 0 !important;
                                overflow-x: hidden !important;
                                will-change: margin-inline-start;
                                backface-visibility: hidden;
                                transition: margin-inline-start 320ms cubic-bezier(0.4, 0, 0.2, 1),
                                            opacity 220ms cubic-bezier(0.4, 0, 0.2, 1) !important;
                            }

                            .fi-sidebar.fi-sidebar-open {
                                margin-inline-start: 0 !important;
                                transform: translateX(0) !important;
                                opacity: 1 !important;
                                visibility: visible !important;
                                pointer-events: auto !important;
                            }

                            .fi-sidebar:not(.fi-sidebar-open) {
                                margin-inline-start: calc(-1 * var(--sidebar-width)) !important;
                                transform: translateX(0) !important;
                                opacity: 0 !important;
                                visibility: hidden !important;
                                pointer-events: none !important;
                                transition: margin-inline-start 320ms cubic-bezier(0.4, 0, 0.2, 1),
                                            opacity 180ms cubic-bezier(0.4, 0, 0.2, 1),
                                            visibility 0s 320ms !important;
                            }

                            .fi-main-ctn {
                                transition: width 320ms cubic-bezier(0.4, 0, 0.2, 1) !important;
                            }
                        }

                        .fi-sidebar-group-collapse-btn {
                            transition: transform 250ms cubic-bezier(0.4, 0, 0.2, 1) !important;
                        }

                        .fi-topbar-open-sidebar-btn,
                        .fi-topbar-collapse-sidebar-btn-ctn {
                            transition: opacity 200ms ease, transform 200ms ease !important;
                        }

                        .fi-sidebar-header-ctn,
                        .fi-sidebar-header,
                        .fi-sidebar-header-logo-ctn,
                        .fi-sidebar .fi-logo {
                            display: none !important;
                        }
                    </style>
                '),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
