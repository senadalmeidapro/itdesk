<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use App\Observers\TicketCommentObserver;
use App\Observers\TicketObserver;
use App\Policies\AssetPolicy;
use App\Policies\TicketPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(Asset::class, AssetPolicy::class);
        Gate::define('manage-settings', fn (User $user) => $user->can('settings.manage'));
        Ticket::observe(TicketObserver::class);
        TicketComment::observe(TicketCommentObserver::class);

    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
