<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;

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
            
            
        Auth::login($user/*, $remember_me*/);
            //Flash::addMessage('Login successful');
            $this->redirect(Auth::getReturnToPage());
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
        Auth::logout();

        //$this->redirect('/login/show-logout-message');

        $this->redirect('/');
    }


}
