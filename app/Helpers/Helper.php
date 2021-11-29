<?php


namespace App\Helpers;


class Helper
{


    public function daysBeforCancelOrder($date): bool
    {
        $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $date);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', now());
        $diff_in_days = $to->diffInDays($from);
        return $diff_in_days >= config('mingo.days_befor_cancel_order');
    }

    public function getName()
    {
        return "Abdelghafour";
    }

}
