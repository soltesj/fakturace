<?php

namespace App\Service;

use DateTime;

class Date
{
    public static function firstDayOfJanuary(): DateTime
    {
        return new DateTime('first day of january');
    }
}