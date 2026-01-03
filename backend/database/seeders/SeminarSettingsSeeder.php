<?php

namespace Database\Seeders;

use App\Models\SeminarSetting;
use Illuminate\Database\Seeder;

class SeminarSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'schedule' => '（日程未定）',
            'zoom_link' => '',
            'line_openchat_link' => '',
            'participation_code' => '',
            'guidance_text' => '',
        ];

        foreach ($settings as $key => $value) {
            SeminarSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
