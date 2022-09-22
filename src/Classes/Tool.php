<?php

namespace App\Classes;

class Tool
{
    public static function createSlug($str, $delimiter='-'):string {

        return strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
    }

    public function parseHourMinute($hourMinute, $short=0):string {
        $hourD = 'Hour';
        $minuteD = 'Minute';
        if ($short) {
            $hourD = 'H';
            $minuteD = 'M';
        }
        $arr = explode(':', $hourMinute);
        return ($arr[0].' '.$hourD.' ').(isset($arr[1]) && $arr[1]!=''?($arr[1].' '.$minuteD):'');
    }

    public function formatPrice($amount, $currency, $symbol=1)
    {
        return ($symbol==2?($currency.'&nbsp;'):'').number_format($amount, 2, '.', ',').($symbol==1?('&nbsp;'.$currency):'');
    }
}