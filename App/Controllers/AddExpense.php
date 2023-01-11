<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;
use \App\Models\Expenses;
use \App\Auth;

/**
 * AddExpense controller
 */
class AddExpense extends \Core\Controller
{
    /**
     * Show the add expense page
     */
    public function newAction()
    {
        $this->requireLogin(); //require login to acces to this page!
        $user= Auth::getUser();
        $user_id = $user->id;
        
        $expenses_cat = Expenses::getExpenseCategories($user_id);
        $pay_method = Expenses::getPaymentMethods($user_id);

        View::renderTemplate('AddExpense/new.html', [
            'expense_cat' => $expenses_cat,
            'pay_method' => $pay_method
        ]);
        //var_dump($user_categories);
    }

    public function createAction()
    {
        $expense = new Expenses($_POST);
        if($expense->save()){
            Flash::addMessage('Expense added successfully');
            $user= Auth::getUser();
            $user_id = $user->id;
        
            $expenses_cat = Expenses::getExpenseCategories($user_id);
            $pay_method = Expenses::getPaymentMethods($user_id);
            View::renderTemplate('AddExpense/new.html', [
                'expense_cat' => $expenses_cat,
                'pay_method' => $pay_method
            ]);
        }else{
            View::renderTemplate('AddExpense/new.html', [
                'expenses' => $expense
              ]);
        }     
    }

    public function limitAction()
    {
        // $category_id = 3;
        echo json_encode(Expenses::getLimitOfCategory($this->route_params['id']), JSON_UNESCAPED_UNICODE);
    }
}
