<?php

namespace App\Models;

use PDO;
use \Core\View;
use \App\Auth;

class Expenses extends \Core\Model
{
    
     // Error messages

    public $errors = []; //tablica z błędami

    /**
     * Class constructor
     * @param array $data  Initial property values
     */
    public function __construct($data = []) //during creating object, assign values from form
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Insert new expense to DB*/
    public function save()
    {
        $this->validate();
        $user_id = $_SESSION['user_id'];  
        $category_id = $this->category;
        $payment_method = $this->payment_method;

        if(empty($this->errors)){
            $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, payment_method_assigned_to_user_id, amount, date_of_expense, expense_comment) 
            VALUES (:user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);
    
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':expense_category_assigned_to_user_id', $category_id, PDO::PARAM_STR);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $payment_method, PDO::PARAM_STR);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);
    
            return $stmt->execute();
        }else{
            return false;
        }
    }

    public function validate(){
         //amount
         if ($this->amount == '') {
            $this->errors[] = 'Amount is required';
        }else if((int)($this->amount>=1000000))
        $this->errors[] = 'Amount sohuld be less than 1000000PLN';

        //date
        if ($this->date == '') {
            $this->errors[] = 'Date is required';
        }/*else if((int)($this->date<wartosc))
        $this->errors[] = 'Date should be after 01.01.2022';*/

        //category
        if ($this->category == '') {
            $this->errors[] = 'Choose category';
        }

        //payment
        if ($this->payment_method == '') {
            $this->errors[] = 'Payment way is required';
        }

        //comment
        if(strlen($this->comment)>100)
            $this->errors[] = 'Comment should be shorter than 100 chars';
    }

    public static function getExpenseCategories($user_id)
    {
        /*$user= Auth::getUser();
        $user_id = $user->id;*/
        $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id=:user_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getPaymentMethods($user_id)
    {
        /*$user= Auth::getUser();
        $user_id = $user->id;*/
        $sql = 'SELECT * FROM payment_methods_assigned_to_users WHERE user_id=:user_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
  
    public static function expensesBalance($user_id, $minDate, $maxDate) {
        $sql = 'SELECT `name`, SUM(`amount`) AS sumOfExpense FROM `expenses`, `expenses_category_assigned_to_users`
        WHERE `expenses`.`expense_category_assigned_to_user_id`=`expenses_category_assigned_to_users`.`id` AND `expenses`.`user_id` = :user_id
        AND `expenses`.`date_of_expense`
        BETWEEN :minDate AND :maxDate GROUP BY `expense_category_assigned_to_user_id` ORDER BY sumOfExpense DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':minDate', $minDate, PDO::PARAM_STR);
        $stmt->bindValue(':maxDate', $maxDate, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getdataPointsExpenses($user_expenses){
        
        $dataPointsExpenses = array();
        foreach ($user_expenses as $expense) {
            $dataPointsExpenses[] = array("label" => $expense['name'], "y" => $expense['sumOfExpense']);
        }
        return $dataPointsExpenses;
    }

    public static function getSumOfExpenses($user_expenses){
        $sumExpenses = 0;
        foreach ($user_expenses as $expense) {
            $sumExpenses += $expense['sumOfExpense'];
        }

        return number_format($sumExpenses, 2, '.', '');
    }

    public static function newExpenseCategory($user_id, $newExpenseName)
    {
        if(!static::checkIfCategoryExists($user_id, $newExpenseName))
        {
            $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name) VALUES (:user_id, :newExpenseName)';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':newExpenseName', $newExpenseName, PDO::PARAM_STR);
            
            return $stmt->execute();
        }else{
            return false;
        }
    }
  
    private static function checkIfCategoryExists($user_id, $newExpenseName)
    {
        $sql = 'SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :user_id AND name = :newExpenseName';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':newExpenseName', $newExpenseName, PDO::PARAM_STR);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }
}
