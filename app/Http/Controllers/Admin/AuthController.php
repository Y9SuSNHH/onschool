<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    private string $title;
    private string $route;

    public function __construct()
    {
//        $route = Route::currentRouteName();
//        $route   = explode('.', $route);
//        $this->title = $route['1'];
        View::share('title', 'Login');
    }

    public function login()
    {
        return view('admin.login');
    }
}
