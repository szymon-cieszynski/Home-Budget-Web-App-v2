<?php
namespace App;


class Dates {
    
    protected $last_day = '';

    public static function getMinMaxDate($period, $currentMonth, $current_year)
    {
        if ($period == 'currentM') {

            $leapyear = static::isLeapYear($current_year);
            $last_day = static::checkDaysOfMonth($leapyear, $currentMonth);

            $minDate = $current_year . '-' . $currentMonth . '-' . '01';
            $maxDate = $current_year . '-' . $currentMonth . '-' . $last_day;
        }

        if ($period == 'prevM') {
            $previousMonth = '';

            if ($current_year == '01') {
                $current_year == '12';
            }

            if ($currentMonth == '01') {
                $previousMonth == '12';
            } else {
                $previousMonth = (int)$currentMonth - 1;
            }

            $leapyear = static::isLeapYear($current_year);
            $last_day = static::checkDaysOfMonth($leapyear, $previousMonth);

            $minDate = $current_year . '-' . $previousMonth . '-' . '01';
            $maxDate = $current_year . '-' . $previousMonth . '-' . $last_day;
        }

        if ($period == 'year') {
            $minDate = $current_year . '-01-01';
            $maxDate = $current_year . '-12-31';
        }

        if ($period == 'custom') {

            $minDate = $_POST['start'];
            $maxDate = $_POST['end'];

            if ($maxDate < $minDate) {

                $_SESSION['error_date'] = "Enter proper time interval!";
            }
        }

        $range = ['minDate' => $minDate, 'maxDate' => $maxDate];

        return $range;
    }

    private static function checkDaysOfMonth($leapyear, $current_month)
    {
        if ($current_month == 4 || $current_month == 6 || $current_month == 9 || $current_month == 11) {
            return $days = 30;
        } else if ($current_month == 2) {
            if ($leapyear == false) {
                return $days = 28;
            } else {
                return $days = 29;
            }
        } else
            return $days = 31;
    }

    private static function isLeapYear($current_year)
    {
        $leapyear = ($current_year % 4 == 0 && $current_year % 100 != 0) || ($current_year % 400 == 0);
        if ($leapyear == true) {
            return true;
        } else {
            return false;
        }
    }
}

