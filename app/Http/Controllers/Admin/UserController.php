<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();

        return back()->with('success', 'User role updated.');
    }

    public function toggleBan(User $user)
    {
        $user->banned = !$user->banned;
        $user->save();

        return back()->with('success', 'User ban status updated.');
    }
}
