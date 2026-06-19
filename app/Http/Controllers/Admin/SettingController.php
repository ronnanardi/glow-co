<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'store_name'     => Setting::get('store_name'),
            'store_tagline'  => Setting::get('store_tagline'),
            'store_whatsapp' => Setting::get('store_whatsapp'),
            'store_email'    => Setting::get('store_email'),
            'store_address'  => Setting::get('store_address'),
            'bank_accounts'  => json_decode(Setting::get('bank_accounts', '[]'), true),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'store_name'     => 'required|string|max:255',
            'store_tagline'  => 'nullable|string|max:500',
            'store_whatsapp' => 'required|string|max:20',
            'store_email'    => 'required|email',
            'store_address'  => 'nullable|string',
        ]);

        Setting::set('store_name', $request->store_name);
        Setting::set('store_tagline', $request->store_tagline);
        Setting::set('store_whatsapp', $request->store_whatsapp);
        Setting::set('store_email', $request->store_email);
        Setting::set('store_address', $request->store_address);

        return back()->with('success', 'Informasi toko berhasil diupdate.');
    }

    public function updateBankAccounts(Request $request)
    {
        $request->validate([
            'banks.*.bank'   => 'required|string',
            'banks.*.number' => 'required|string',
            'banks.*.name'   => 'required|string',
        ]);

        Setting::set('bank_accounts', json_encode($request->banks));

        return back()->with('success', 'Rekening bank berhasil diupdate.');
    }
}