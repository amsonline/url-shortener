<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Add any custom logic here

        if ($this->attemptLogin($request)) {
            // Handle successful login
            return $this->sendLoginResponse($request);
        }

        // Handle failed login
        return $this->sendFailedLoginResponse($request);
    }

}
