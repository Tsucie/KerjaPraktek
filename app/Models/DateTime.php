<?php

namespace App\Models;

class DateTime
{
    public static function Now()
    {
        date_default_timezone_set('Asia/Jakarta');
        return date('Y-m-d H:i:s');
    }
}
