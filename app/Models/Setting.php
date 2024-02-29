<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    private static $settings = null;

    protected $fillable = ['value'];
    public $timestamps = false;

    private static function getAllSettings() {
        $rawSettings = self::all();
        self::$settings = [];
        foreach ($rawSettings as $record) {
            self::$settings[$record->key] = $record->value;
        }
    }

    public static function getSetting($key, $default = null) {
        if (self::$settings === null) {
            self::getAllSettings();
        }

        return self::$settings[$key] ?? $default;
    }

    public static function setSetting($key, $value) {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getAvailableSocials() {
        return explode(',', self::getSetting('social'));
    }
}
