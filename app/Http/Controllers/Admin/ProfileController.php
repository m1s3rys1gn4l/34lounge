<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $data = $request->validateWithBag('updateEmail', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return redirect()->route('admin.profile.edit')->with('status', 'Account details updated.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update(['password' => $request->input('password')]);

        return redirect()->route('admin.profile.edit')->with('status', 'Password updated.');
    }
}
