<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

use \App\Flash;

/**
 * AddIncome controller
 */
class AddIncome extends \Core\Controller
{
    /**
     * Show the add income page
     */
    public function newAction()
    {
        View::renderTemplate('AddIncome/new.html');
    }

    public function createAction()
    {
       
    }

    public function destroyAction()
    {
        
    }
    /**
     * Show a "add income" flash message??? 
     */
    public function showAddIncomeMessageAction()
    {
       
    }

}
