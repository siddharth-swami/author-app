<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $response = Http::post( env('THIRD_PARTY_API_URL')."/token", [
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if ($response->successful()) {
            $data = $response->json();
            Session::put('token', $data['token_key']);
            Session::put('refresh_token', $data['refresh_token_key']);
            Session::put('user', $data['user']);
            Session::put('expires_at', strtotime($data['expires_at']));
            Session::put('refresh_expires_at', strtotime($data['refresh_expires_at']));
            return redirect('/dashboard');
        } else {
            return back()->withErrors(['login' => 'Invalid credentials']);
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
