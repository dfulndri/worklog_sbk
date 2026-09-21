<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $layout = Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.karyawan';

        return view('profile.edit', ['user' => Auth::user(), 'layout' => $layout]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! $user->isAdmin() && $user->employee) {
            $user->employee->update(['phone' => $data['phone'] ?? null]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = Auth::user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar_path' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function updateCover(Request $request)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $request->validate([
            'cover' => ['required', 'image', 'max:4096'],
        ]);

        $user = Auth::user();

        if ($user->cover_path) {
            Storage::disk('public')->delete($user->cover_path);
        }

        $path = $request->file('cover')->store('covers', 'public');
        $user->update(['cover_path' => $path]);

        return back()->with('success', 'Foto latar berhasil diperbarui.');
    }

    public function editAccount()
    {
        $layout = Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.karyawan';

        return view('profile.account-settings', ['layout' => $layout]);
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
