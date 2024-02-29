<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Url extends Model
{
    use HasFactory;

    public static function getFreeUrl() {
        do {
            $url = self::generateRandomString();
        }while(DB::table('urls')->where('shortenurl', $url)->count() > 0);
        return $url;
    }

    public static function generateRandomString($length = -1)
    {
        if ($length == -1) {
            $length = Setting::getSetting('url_length');
        }
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_-';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }
}
