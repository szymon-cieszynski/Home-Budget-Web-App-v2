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
    public function settingsAction()
    {
        $user_id = $this->user->id;

        View::renderTemplate('Settings/settings.html', [
            'user' => $this->user,
            'income_cat' => Incomes::getIncomeCategories($user_id)
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editAction()
    {
        View::renderTemplate('Profile/editIncomesCat.html', [
            'user' => $this->user,
            // 'income_cat' => $incomes_cat
        ]);
    }

    /**
     * Update the profile
     *
     * @return void
     */
    public function updateAction()
    {
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Changes saved');

            $this->redirect('/profile/show');
        } else {

            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);
        }
    }
}
