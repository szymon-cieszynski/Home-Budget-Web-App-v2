<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;
use \App\Models\Expenses;
use \App\Auth;

/**
 * Balance controller
 */
class Balance extends \Core\Controller
{
    /**
     * Show the add expense page
     */
    public function newAction()
    {
        $this->requireLogin();

        View::renderTemplate('Balance/balance.html');
    }

    public function createAction()
    {
    

        
    }
}
