<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;
use \App\Models\Incomes;
use \App\Auth;

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
        $user= Auth::getUser();
        $user_id = $user->id;
        
        $incomes_cat = Incomes::getIncomeCategories($user_id);
        View::renderTemplate('AddIncome/new.html', [
            'income_cat' => $incomes_cat
        ]);
        //var_dump($user_categories);
    }

    public function createAction()
    {
        $income = new Incomes($_POST);
        if($income->save()){
            Flash::addMessage('Income added successfully');
            View::renderTemplate('AddIncome/new.html');
        }else{
            //wyswietli ten sam formularz dla new lecz z błędami jakie sie pojawiły
            View::renderTemplate('AddIncome/new.html', [
                'incomes' => $income
              ]);
        }

        
    }
}
