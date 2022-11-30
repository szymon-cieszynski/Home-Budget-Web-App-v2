<?php

namespace App\Controllers;

/**
 * Authenticated base controller
 */
abstract class Authenticated extends \Core\Controller
{
    /**
     * Require the user to be authenticated before giving access to all methods in the all controllers where we need to
     *
     * @return void
     */
    protected function before()
    {
        $this->requireLogin();
    }
}
