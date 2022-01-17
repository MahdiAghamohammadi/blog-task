<?php

use Morilog\Jalali\Jalalian;

function jalaliDate($date, $format = 'H:i:s Y/m/d')
{
    return Jalalian::forge($date)->format($format);
}