<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => Setting::current(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'location' => ['required', 'string', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'show_arabic' => ['nullable', 'boolean'],
            'show_placeholder' => ['nullable', 'boolean'],
            'enable_popups' => ['nullable', 'boolean'],
            'enable_whatsapp_order' => ['nullable', 'boolean'],
        ]);

        foreach (['show_arabic', 'show_placeholder', 'enable_popups', 'enable_whatsapp_order'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        Setting::current()->update($data);

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated.');
    }
}
