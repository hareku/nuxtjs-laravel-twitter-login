<?php

namespace App\Support\Socialite;

use App\Entities\Auth\SocialAccount;
use App\Entities\Auth\User;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\One\User as SocialiteOneUser;

class SocialiteHandler
{
    /**
     * @return string
     */
    public function getRedirectToTwitterUrl(): string
    {
        $redirectResponse = Socialite::driver('twitter')->redirect();

        return $redirectResponse->getTargetUrl();
    }

    /**
     * @return JsonResponse
     */
    public function handleTwitterCallback(): JsonResponse
    {
        try {
            return response()->json($this->getCredentialsByTwitter());
        } catch (InvalidArgumentException $e) {
            return $this->errorJsonResponse('Twitterでの認証に失敗しました。');
        } catch (EmailAlreadyExistsException $e) {
            return $this->errorJsonResponse(
                "{$e->getEmail()} は既に使用されているEメールアドレスです。"
            );
        }
    }

    /**
     * @return array
     */
    protected function getCredentialsByTwitter(): array
    {
        $twitterUser = Socialite::driver('twitter')->user();

        $socialAccount = SocialAccount::firstOrNew([
            'provider'   => 'twitter',
            'account_id' => $twitterUser->getId(),
        ]);

        $user = $this->resolveUser($socialAccount, $twitterUser);

        return [
            'user'         => $user,
            'access_token' => $user->createToken(null, ['*'])->accessToken,
        ];
    }

    /**
     * @param  SocialAccount  $socialAccount
     * @param  SocialiteOneUser  $twitterUser
     * @return User
     */
    protected function resolveUser(SocialAccount $socialAccount, SocialiteOneUser $twitterUser): User
    {
        if ($socialAccount->exists) {
            return User::find($socialAccount->getAttribute('user_id'));
        }

        if (User::where('email', $twitterUser->getEmail())->exists()) {
            throw new EmailAlreadyExistsException($twitterUser->getEmail());
        }

        $createdUser = User::create([
            'name'         => $twitterUser->getName(),
            'email'        => $twitterUser->getEmail(),
            'password'     => null,
            // 'twitter_id'   => $twitterUser->getNickName(),
        ]);

        $socialAccount->setAttribute('user_id', $createdUser->id);
        $socialAccount->save();

        return $createdUser;
    }

    /**
     * @param  string  $message
     * @return JsonResponse
     */
    protected function errorJsonResponse(string $message): JsonResponse
    {
        return response()->json(compact('message'), 400);
    }
}
