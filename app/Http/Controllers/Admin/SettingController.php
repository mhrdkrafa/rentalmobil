<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function notifications()
    {
        $templates = [
            'wa_template_new_booking' => Setting::where('key', 'wa_template_new_booking')->value('value'),
            'wa_template_payment_verified' => Setting::where('key', 'wa_template_payment_verified')->value('value'),
        ];
        
        return view('admin.settings.notifications', compact('templates'));
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'wa_template_new_booking' => 'required|string',
            'wa_template_payment_verified' => 'required|string',
        ]);

        Setting::updateOrCreate(
            ['key' => 'wa_template_new_booking'],
            ['value' => $request->wa_template_new_booking]
        );

        Setting::updateOrCreate(
            ['key' => 'wa_template_payment_verified'],
            ['value' => $request->wa_template_payment_verified]
        );

        return back()->with('success', 'Template notifikasi berhasil diperbarui.');
    }
}
