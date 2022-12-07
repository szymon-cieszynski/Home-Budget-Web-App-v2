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
            $user_id = User::getNewUserId();
            User::copyIncomesCategories($user_id);
            User::copyExpensesCategories($user_id);
            User::copyPaymentMethods($user_id);
            
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

  /* Activate new account */

  public function activateAction()
  {
    User::activate($this->route_params['token']);
    $this->redirect('/signup/activated');
  }


  /* Show page for activated account */
  public function activatedAction()
  {
    View::renderTemplate('Signup/activated.html');
  }
}