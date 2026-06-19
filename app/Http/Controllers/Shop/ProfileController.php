<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('customer.shop.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Kalau tidak ada file yang diupload, hapus field avatar dari request
        // supaya tidak trigger validasi 'image'
        if (!$request->hasFile('avatar')) {
            $request->request->remove('avatar');
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'         => 'nullable|string|max:20',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
            'remove_avatar' => 'nullable|in:0,1',
        ]);

        $avatarPath = $user->avatar;

        if ($request->remove_avatar == '1' && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $avatarPath = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'avatar' => $avatarPath,
        ]);

        return back()->with('success', 'Profil berhasil diupdate.');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password'      => 'required',
            'password'               => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}