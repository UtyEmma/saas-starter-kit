<?php

namespace App\Jobs;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckForExpiredSubscriptions implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SubscriptionService $susbcriptionService): void {
        $expiredSubscriptions = $susbcriptionService->expiredSubscriptions(today()->subDay());

        $expiredSubscriptions->each(function($subscription) use($susbcriptionService) {
            try {
                $susbcriptionService->handleExpiredSubscriptions($subscription);
            } catch (\Exception $e) {
                Log::error('Error handling expired subscription: ' . $e->getMessage());
            }
        });
    }
}
