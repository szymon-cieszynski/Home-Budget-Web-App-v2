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

    public function editIncomesAction()
    {
        if (Incomes::editIncomesCat($_POST['category'], $_POST['newIncomeName'])) {

            Flash::addMessage('Changes saved');
            $this->redirect('/settings/show');
        } else {

            Flash::addMessage('Could not save changes!', FLASH::WARNING);
            View::renderTemplate('/settings/settings.html', [
                /*'user' => $this->user*/
            ]);
        }
    }
}
