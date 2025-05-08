<?php

namespace App\Http\Helper;

class Helper
{
    /**
     * Check if one or more guards are logged in.
     *
     * @param array|string $guards
     * @return bool
     */
    public static function isLoggedIn($guards = null): bool
    {
        if (!$guards) {
            $validGuards = config('auth.guards');
            $guards = array_keys($validGuards);

            if (count($guards) === 1) {
                $guards = $guards[0];
            }

            if (is_string($guards)) {
                $guards = [$guards];
            }

            foreach ($guards as $guard) {
                if (\Auth::guard($guard)->check()) {
                    return true;
                }
            }

            return false;
        } else {
            if (is_string($guards)) {
                $guards = [$guards];
            }

            foreach ($guards as $guard) {
                if (\Auth::guard($guard)->check()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the logged-in user for a specific guard.
     *
     * @param string $guard
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function getLoggedInUser($guard = null)
    {
        if ($guard) {
            return \Auth::guard($guard)->user();
        }

        $validGuards = config('auth.guards');
        $guards = array_keys($validGuards);

        foreach ($guards as $guard) {
            if (\Auth::guard($guard)->check()) {
                return \Auth::guard($guard)->user();
            }
        }

        return null;
    }
}
