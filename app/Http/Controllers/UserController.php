<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login(Request $request): RedirectResponse|string
    {
        $success = Auth::attempt([
            "email" => $request->query("email", "wrong"),
            "password" => $request->query("password", "wrong")
        ], true);

        if ($success) {
            Session::regenerate();
            return redirect('/users/current');
        } else {
            return "Wrong credentials";
        }
    }

    public function current(): string
    {
        $user = Auth::user();
        if ($user) {
            return "Hello, " . $user->name;
        } else {
            return "You are not logged in";
        }
    }
}
