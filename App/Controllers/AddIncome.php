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
        $this->requireLogin(); //require login to acces to this page!
        
        View::renderTemplate('AddIncome/new.html');
        
        //$user_categories = User::getCategories();
        //var_dump($user_categories);
      
        
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
