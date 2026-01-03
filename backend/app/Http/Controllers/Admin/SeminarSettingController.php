<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeminarSetting;
use Illuminate\Http\Request;

class SeminarSettingController extends Controller
{
    public function edit()
    {
        $settings = SeminarSetting::getAll();
        $keys = SeminarSetting::KEYS;

        return view('admin.seminar-settings.edit', compact('settings', 'keys'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'schedule' => ['nullable', 'string', 'max:500'],
            'zoom_link' => ['nullable', 'url', 'max:500'],
            'line_openchat_link' => ['nullable', 'url', 'max:500'],
            'participation_code' => ['nullable', 'string', 'max:100'],
            'guidance_text' => ['nullable', 'string', 'max:2000'],
        ]);

        foreach ($validated as $key => $value) {
            SeminarSetting::setValue($key, $value);
        }

        SeminarSetting::clearCache();

        return redirect()->route('admin.seminar-settings.edit')
            ->with('success', 'セミナー設定を更新しました。');
    }
}
