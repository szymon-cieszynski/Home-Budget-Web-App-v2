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
        if (isset($_POST['period'])){
            $user_id = $_SESSION['logged_id'];
            $period = $_POST['period'];
            $current_month = date('m');
            $current_year = date('Y');

            $range = Dates::getMinMaxDate($period, $current_month, $current_year); //function in another file operationOnDates.php

            $minDate = $range['minDate'];
            $maxDate = $range['maxDate'];

        }

        
    }
}
