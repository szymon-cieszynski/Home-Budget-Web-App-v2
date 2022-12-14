<?php

namespace App\Controllers;

use \Core\View;
use \App\Flash;
use \App\Models\Incomes;
use \App\Models\Expenses;
use \App\Auth;
use \App\Dates;

/**
 * Balance controller
 */
class Balance extends \Core\Controller
{
    /**
     * Show the expense page
     */
    public function newAction()
    {
        $this->requireLogin();

        View::renderTemplate('Balance/balance.html');
    }

    public function createAction()
    {
        if (isset($_POST['period'])){
            $user_id = $_SESSION['user_id']; ;
            $period = $_POST['period'];
            $current_month = date('m');
            $current_year = date('Y');

            $range = Dates::getMinMaxDate($period, $current_month, $current_year);

            $minDate = $range['minDate'];
            $maxDate = $range['maxDate'];

            $user_incomes = Incomes::incomesBalance($user_id, $minDate, $maxDate);
            $user_expenses = Expenses::expensesBalance($user_id, $minDate, $maxDate);
            $_SESSION['user_incomes'] = $user_incomes;
            $dataPointsIncomes = Incomes::getdataPointsIncomes($user_incomes);
            $dataPointsExpenses = Expenses::getdataPointsExpenses($user_expenses);

            $sumIncomes = Incomes::getSumOfIncomes($user_incomes);
            $sumExpenses = Expenses::getSumOfExpenses($user_expenses);
            
            //var_dump($dataPointsIncomes);

            View::renderTemplate('Balance/balance.html',[
                'user' => $_SESSION['user_id'],
                'user_incomes' => $user_incomes,
                'user_expenses' => $user_expenses, 
                'min_date' => $minDate,
                'max_date' => $maxDate,
                'dataPointsIncomes' => $dataPointsIncomes,
                'dataPointsExpenses' => $dataPointsExpenses,
                'sumIncomes' => $sumIncomes,
                'sumExpenses' => $sumExpenses

            ]);

        }       
    }

}
