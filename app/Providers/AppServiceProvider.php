<?php

namespace App\Providers;

use App\View\Components\Button\BackButton;
use App\View\Components\Button\CreateButton;
use App\View\Components\Button\DeleteButton;
use App\View\Components\Button\EditButton;
use App\View\Components\Button\InfoButton;
use App\View\Components\Button\InvoiceButton;
use App\View\Components\Button\PrintButton;
use App\View\Components\Button\ResetButton;
use App\View\Components\Button\ShowButton;
use App\View\Components\Table\Action;
use App\View\Components\Table\Table;
use App\View\Components\Table\Td;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Barryvdh\Debugbar\Facades\Debugbar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        URL::forceScheme(env('FORCE_SCHEME', 'https'));
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();
        env('APP_DEBUG_BAR', false) ? Debugbar::enable() : Debugbar::disable();
        Blade::components([
            'table'         => Table::class,
            'td'            => Td::class,
            'action'        => Action::class,
            'filter'        => Filter::class,
            'btn-create'    => CreateButton::class,
            'btn-edit'      => EditButton::class,
            'btn-delete'    => DeleteButton::class,
            'btn-back'      => BackButton::class,
            'btn-show'      => ShowButton::class,
            'btn-info'      => InfoButton::class,
            'btn-print'     => PrintButton::class,
            'btn-reset'     => ResetButton::class,
            'btn-invoice'   => InvoiceButton::class,
        ]);
    }
}
