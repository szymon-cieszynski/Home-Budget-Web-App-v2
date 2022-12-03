<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Signup extends \Core\Controller
{

    public function newAction()
    {
    View::renderTemplate('Signup/new.html');
    }

    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {
            $user->sendActivationEmail();            
            $this->redirect('/signup/success');
            
          } else {
            //wyswietli ten sam formularz dla new lecz z błędami jakie sie pojawiły
            View::renderTemplate('Signup/new.html', [
              'user' => $user
            ]);
          }
        
    }

      /**
   * Show the signup success page
   *
   * @return void
   */
  public function successAction()
  {
    View::renderTemplate('Signup/success.html');
  }
}