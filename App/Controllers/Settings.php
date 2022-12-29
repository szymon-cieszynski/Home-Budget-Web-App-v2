<?php

namespace App\Controllers;

use \App\Auth;

use \Core\View;
use \App\Flash;
use \App\Models\Incomes;

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
            'income_cat' => Incomes::getIncomeCategories($user_id)
        ]);
    }

    public function newIncomeCategoryAction()
    {
        $user_id = $this->user->id;
        if (Incomes::newIncomeCategory($user_id, $_POST['newIncomeCatName'])) {

            Flash::addMessage('New category added successfully!');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Category already exists!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }

    public function editIncomesAction()
    {
        if (Incomes::editIncomesCat($_POST['categoryIncomes'], $_POST['newIncomeName'])) {

            Flash::addMessage('Changes saved');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not save changes!', FLASH::WARNING);
            $this->redirect('/settings/show');
        }
    }

    public function deleteIncomesAction()
    {
        if (Incomes::deleteIncomesCat($_POST['categoryIncomes'])) {

            Flash::addMessage('Category deleted succesfully');
            $this->redirect('/settings/show');
        } else {
            Flash::addMessage('Could not delete category!', FLASH::DANGER);
            $this->redirect('/settings/show');
        }
    }


}
