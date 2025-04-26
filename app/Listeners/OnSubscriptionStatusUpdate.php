<?php

namespace App\Listeners;

use App\Enums\SubscriptionStatus;
use App\Events\Subscriptions\SubscriptionStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OnSubscriptionStatusUpdate
{
    /**
     * Create the event listener.
     */
    public function __construct() {
        //s
    }

    /**
     * Handle the event.
     */
    public function handle(SubscriptionStatusUpdated $event): void {
        $subscription = $event->subscription;
        $subscription->load('user');

        match($subscription->status) {
            SubscriptionStatus::EXPIRED => $this->onExpired($subscription),
            SubscriptionStatus::GRACE => $this->onGrace($subscription),
            default => null,
        };
    }

    function onExpired($subscription) {
        //Send Subscription expired notification
        notify("Your subscription has expired")
            ->line("We wanted to let you know that your subscription has fully expired, and access to our services has been suspended. To continue enjoying our platform, you'll need to create a new subscription.")
            ->action('Manage Billing', route('billing'))
            ->priority(1)
            ->send($subscription->user, ['mail']);
    }

    function onGrace($subscription) {
        notify("Your subscription has expired.")
            ->line("We wanted to let you know that your subscription has expired, and your account is on grace period until {$subscription->grace_ends_at->format('jS F Y')}. To continue enjoying our platform, you'll need to create a new subscription.")
            ->action('Manage Billing', route('billing'))
            ->priority(1)
            ->send($subscription->user, ['mail']);
    }

}
