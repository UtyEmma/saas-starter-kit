<?php

namespace App\Providers;

use App\Models\Features\Feature;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::before(function(User $user, string $ability){
            if($feature = Feature::where('feature_class', $ability)
                            ->orWhere('shortcode', $ability)->first()) {
                $response = $feature->check($user);
    
                if($response->failed()) {
                    return Response::deny($response->message());
                } 

                return Response::allow();
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
