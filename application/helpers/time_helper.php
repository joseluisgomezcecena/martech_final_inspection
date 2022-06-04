<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('readable_elapsed_time')) {

    function readable_elapsed_time($str_end_date, $str_start_date)
    {
        //2022-05-25 13:53:58

        if ($str_end_date == null || $str_start_date == null) {
            return '';
        }

        $start_date = DateTime::createFromFormat('Y-m-d H:i:s', $str_start_date);
        $end_date = DateTime::createFromFormat('Y-m-d H:i:s', $str_end_date);

        $interval = date_diff($end_date, $start_date);

        $output_string = '';
        if ($interval->days != 0 && $interval->days != FALSE) {
            $output_string .=  strval($interval->days) . 'd';
        }

        $output_string .= ' ' . strval($interval->h)  .  'h';

        $output_string .= ' ' . strval($interval->i) .  'm';

        return trim($output_string);
    }



    function convert_time_string_to_float($time_str)
    {
        if ($time_str == null)
            return '';

        //$time_str example 09:01:26
        $time_parts = explode(":", $time_str);

        $hours = floatval(intval($time_parts[0]));

        //Si 1       60
        //            25
        $mins = floatval(intval($time_parts[1])) / 60.0;

        return round($hours + $mins, 2);
    }
}
