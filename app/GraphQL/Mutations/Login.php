<?php

namespace App\GraphQL\Mutations;
use Arr;
use GraphQL\Error\Error;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

final class Login
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): string
    {
        // Plain Laravel: Auth::guard()
        // Laravel Sanctum: Auth::guard(Arr::first(config('sanctum.guard')))
        $guard =  Auth::guard(Arr::first(config('sanctum.guard')));

        if( ! $guard->attempt($args)) {
            throw new Error('Invalid credentials.');
        }

        /**
         * Since we successfully logged in, this can no longer be `null`.
         *
         * @var \App\Models\User $user
         */
        $user = $guard->user();

        return $user
            ->createToken($user->$args['email'])
            ->plainTextToken;
    }
}