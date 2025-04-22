<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use phpDocumentor\Reflection\Types\Nullable;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');
        $country = locale()->country();
        $toast = $this->toast();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'toast' => $toast,
            'auth' => [
                'user' => authenticated(['plan', 'subscription']),
            ],
            'locale' => [
                'country' => $country,
                'currency' => $country->currency
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }

    function toast(){
        $request = request();
        if(!$toast = $request->session()->get('toast')) {
            if($request->session()->has('success')) {
                return toast($request->session()->get('success'), $request->session()->get('title', null))->success()->get();
            }

            if($request->session()->has('error')) {
                return toast($request->session()->get('error'), $request->session()->get('title', null))->error()->get();
            }

            if($request->session()->has('info')) {
                return toast($request->session()->get('info'), $request->session()->get('title', null))->info()->get();
            }

            if($request->session()->has('warning')) {
                return toast($request->session()->get('warning'), $request->session()->get('title', null))->warning()->get();
            }
        }

        return $toast;
    }
}
