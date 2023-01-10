<?php

namespace App\Controllers;

use \App\Auth;

use \Core\View;
use \App\Flash;
use \App\Models\Incomes;
use \App\Models\Expenses;

/**
 * Settings controller
 */
class Settings extends Authenticated
{
    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser(); //unikamy redundacji kodu
    }

    /**
     * Show settings sections
     *
     * @return void
     */
    public function showAction()
    {
        $user_id = $this->user->id;

        View::renderTemplate('Settings/settings.html', [
            /*'user' => $this->user,*/
            'income_cat' => Incomes::getIncomeCategories($user_id),
            'expense_cat' => Expenses::getExpenseCategories($user_id),
            'pay_method' => Expenses::getPaymentMethods($user_id),
            var_dump(Expenses::getExpenseCategories($user_id))
        ]);
    }

    public function newIncomeCategoryAction()
    {
        $user_id = $this->user->id;
        if (Incomes::newIncomeCategory($user_id, $_POST['newIncomeCatName'])) {

            Flash::addMessage('New category added successfully.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Category already exists!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }

    public function editIncomesAction()
    {
        if (Incomes::editIncomesCat($_POST['categoryIncomes'], $_POST['editIncomeName'])) {

            Flash::addMessage('Changes saved.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not save changes!', FLASH::WARNING);
            $this->redirect('/settings/show');
        }
    }

    public function deleteIncomesAction()
    {
        if (Incomes::deleteIncomesCat($_POST['categoryIncomes'])) {

            Flash::addMessage('Category deleted succesfully.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not delete category!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }

    public function newExpenseCategoryAction()
    {
        $user_id = $this->user->id;
        if (Expenses::newExpenseCategory($user_id, $_POST['newExpenseCatName'])) {

            Flash::addMessage('New category added successfully.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Category already exists!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }

    public function editExpensesAction()
    {
        if (Expenses::editExpensesCat($_POST['categoryExpenses'], $_POST['editExpenseName'])) {

            Flash::addMessage('Changes saved.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not save changes!', FLASH::WARNING);
            $this->redirect('/settings/show');
        }
    }

    public function deleteExpensesAction()
    {
        if (Expenses::deleteExpensesCat($_POST['categoryExpenses'])) {

            Flash::addMessage('Category deleted succesfully.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not delete category!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }

    public function setLimitAction()
    {
        if (Expenses::setLimit($_POST['categoryExpenses'], $_POST['limit'])) {

            Flash::addMessage('Limit added succesfully.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not set limit!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }

    public function newPaymentMethodAction()
    {
        $user_id = $this->user->id;
        if (Expenses::newPaymentMethod($user_id, $_POST['newPayMethodName'])) {

            Flash::addMessage('New payment method added successfully.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Payment method already exists!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }

    public function editPaymentMethodAction()
    {
        if (Expenses::editPaymentMethod($_POST['paymentMethods'], $_POST['editMethodName'])) {

            Flash::addMessage('Changes saved.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not save changes!', FLASH::WARNING);
            $this->redirect('/settings/show');
        }
    }

    public function deletePaymentMethodAction()
    {
        if (Expenses::deletePaymentMethod($_POST['paymentMethods'])) {

            Flash::addMessage('Payment method deleted succesfully.');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not delete payment method!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }
}
