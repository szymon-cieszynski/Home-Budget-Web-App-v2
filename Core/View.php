<?php

namespace Core;

/**
 * View
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

   /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [])
    {
        echo static::getTemplate($template, $args);
    }
    /**
     * Get the contents of a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return string
     */
    public static function getTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
            //$twig->addGlobal('session', $_SESSION); //zmienna sesyjna do TWIG'a by mógł wyświetlić kto zalogowany
            //$twig->addGlobal('is_logged_in', \App\Auth::isLoggedIn()); juz nam nie potrzebna bo sprawdzamy metodą getUser
            $twig->addGlobal('current_user', \App\Auth::getUser());
            $twig->addGlobal('flash_messages', \App\Flash::getMessages());
            //$twig->addGlobal('income_cat', \App\Models\Incomes::getIncomeCategories());
            //$twig->addGlobal('expense_cat', \App\Models\Expenses::getExpenseCategories()); 
            //$twig->addGlobal('pay_method', \App\Models\Expenses::getPaymentMethods());
        }

        return $twig->render($template, $args);
    }
}
