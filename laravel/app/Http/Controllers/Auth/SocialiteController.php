<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\Socialite\SocialiteHandler;
use Illuminate\Http\JsonResponse;

class SocialiteController extends Controller
{
    /**
     * @var SocialiteHandler
     */
    protected $socialiteHandler;

    /**
     * @param  SocialiteHandler  $socialiteHandler
     * @return void
     */
    public function __construct(SocialiteHandler $socialiteHandler)
    {
        $this->middleware('session');

        $this->socialiteHandler = $socialiteHandler;
    }

    /**
     * @return JsonResponse
     */
    public function getRedirectToTwitterUrl(): JsonResponse
    {
        return response()->json([
            'redirect_url' => $this->socialiteHandler->getRedirectToTwitterUrl(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function handleTwitterCallback(): JsonResponse
    {
        return $this->socialiteHandler->handleTwitterCallback();
    }
}
