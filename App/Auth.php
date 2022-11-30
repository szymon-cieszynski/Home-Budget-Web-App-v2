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
        //$_SESSION['username'] = $user->username;

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


        /**
     * Remember the originally-requested page in the session
     *
     * @return void
     */
    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    /**
     * Get the originally-requested page to return to after requiring login, or default to the homepage
     *
     * @return void
     */
    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/'; //if this value doesn't exist in session, redirect to the home page!
    }

    /*Get current user */
    public static function getUser()
    {
        if (isset($_SESSION['user_id']))
        {
            return User::findById($_SESSION['user_id']);
        }
    } 
}