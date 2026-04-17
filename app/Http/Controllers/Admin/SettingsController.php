<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $keys = [
        'shop_name',
        'shop_address',
        'shop_phone',
        'currency_symbol',
        'tax_rate',
        'receipt_footer',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->keys as $key) {
            $settings[$key] = Setting::get($key, '');
        }
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'shop_name'       => 'required|string|max:255',
            'shop_address'    => 'nullable|string',
            'shop_phone'      => 'nullable|string|max:30',
            'currency_symbol' => 'required|string|max:5',
            'tax_rate'        => 'required|numeric|min:0|max:100',
            'receipt_footer'  => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::record('updated', 'Updated system settings');
        return back()->with('success', 'Settings saved successfully.');
    }
}
