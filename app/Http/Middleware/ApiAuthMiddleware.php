<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $currentTime = time();
        if (!Session::has('token')) {
            return response()->json(['error' => 'Unauthorized. Please log in.'], 401);
        }

        // if (Session::has('expires_at') && $currentTime > Session::get('expires_at')) {
        //     if (Session::has('refresh_expires_at') && $currentTime < Session::get('refresh_expires_at')) {
        //         $response = Http::get(config('services.thirdparty_api.api') . '/token/refresh/'.Session::get('refresh_token'));

        //         if ($response->successful()) {
        //             $data = $response->json();
        //             Session::put('token', $data['token_key']);
        //             Session::put('refresh_token', $data['refresh_token_key']);
        //             Session::put('expires_at', strtotime($data['expires_at']));
        //             Session::put('refresh_expires_at', strtotime($data['refresh_expires_at']));
        //         } else {
        //             return redirect('/logout')->withErrors(['error' => 'Session expired. Please log in again.']);
        //         }
        //     } else {
        //         return redirect('/logout')->withErrors(['error' => 'Session expired. Please log in again.']);
        //     }
        // }
        return $next($request);
    }
}
