<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

if (!function_exists('setActive')) {
    function setActive(array $routes, $activeClass = 'active'): string
    {
        return request()->routeIs($routes) ? $activeClass : '';
    }
}

if (!function_exists('user')) {
    function user(): User
    {
        return Auth::user();
    }
}
