<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\GamificationService;

class LogUserLoginListener
{
    protected GamificationService $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if ($event->user) {
            $this->gamificationService->recordDailyLogin($event->user, request()->ip());
        }
    }
}
