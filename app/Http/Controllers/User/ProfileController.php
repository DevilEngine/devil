<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('user.edit-profile', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        $user = auth()->user();
    
        $data = [
            'bio' => $request->input('bio'),
        ];
    
        // ✅ Avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
    
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $path;
        }
    
        // ✅ Suppression demandée
        if ($request->filled('delete_avatar') && $user->avatar_path) {
            if (Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }
    
            $data['avatar_path'] = null;
        }
    
        // ✅ Changement de mot de passe si rempli
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        }
    
        $user->update($data);
    
        return redirect()
            ->route('user.profile.edit')
            ->with('success', 'Profile updated.');
    }    

    public function usage(){

        $usages = auth()->user()->devilcoinUsages()->latest()->get();

        return view('user.usage')->with(compact('usages'));

    }
}
