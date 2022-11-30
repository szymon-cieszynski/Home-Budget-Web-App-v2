<?php

namespace App;

use App\Models\User;
use App\Models\RememberedLogin;

/**
 * Authentication
 *
 * PHP version 7.0
 */
class Auth
{
    /**
     * Login the user
     *
     * @param User $user The user model
     * @param boolean $remember_me Remember the login if true
     *
     * @return void
     */
    public static function login($user/*, $remember_me*/)
    {
        session_regenerate_id(true); //generuje jeszcze jedno ID sesji by uniknąć ataku hakera który podrzuci nam swoje ID

        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;

        /*if ($remember_me) {

            if ($user->rememberLogin()) {

                setcookie('remember_me', $user->remember_token, $user->expiry_timestamp, '/');
            }
        }*/
    }


    /**
     * Logout the user
     *
     * @return void
     */
    public static function logout()
    {
        // Unset all of the session variables
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Finally destroy the session
        session_destroy();

        //static::forgetLogin();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}