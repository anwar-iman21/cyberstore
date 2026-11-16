<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $keys = [
        'company_name', 'company_description', 'whatsapp_number',
        'email', 'payment_information', 'invoice_information',
    ];

    public function edit()
    {
        $settings = collect($this->keys)->mapWithKeys(fn ($key) => [$key => Setting::get($key)]);
        $settings['company_logo'] = Setting::get('company_logo');

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_description' => ['nullable', 'string'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'payment_information' => ['nullable', 'string'],
            'invoice_information' => ['nullable', 'string'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:1024'],
        ]);

        if ($request->hasFile('company_logo')) {
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('company_logo', $request->file('company_logo')->store('settings', 'public'));
        }

        unset($data['company_logo']);
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
