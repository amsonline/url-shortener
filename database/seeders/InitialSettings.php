<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialSettings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'site_name'     => 'URL Shortener',
            'qr_eye'        => 'circle',
            'qr_quality'    => 'Q',
            'qr_style'      => 'round',
            'qr_size'       => 100,
            'social'        => 'facebook,twitter,instagram',
        ];

        $records = [];
        foreach ($settings as $key => $value) {
            $records[] = [
                'key'   => $key,
                'value' => $value,
            ];
        }

        DB::table('settings')->insert($records);
    }
}
