<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Login controller
 */
class Login extends \Core\Controller
{
    /**
     * Show the login page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

       // $remember_me = isset($_POST['remember_me']);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_name'] = $user->username;
            $this->redirect('/');
            /*Auth::login($user, $remember_me);
            Flash::addMessage('Login successful');
            $this->redirect(Auth::getReturnToPage());*/
        } else {
           //Flash::addMessage('Login not successful, please try again', Flash::WARNING);
            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email']/*,
                'remember_me' => $remember_me*/
            ]);
        }
    }

    //Log out - zniszczenie sesji
    public function destroyAction()
    {
        //Auth::logout();

        //$this->redirect('/login/show-logout-message');

        $_SESSION = [];

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        $this->redirect('/');
    }


}
