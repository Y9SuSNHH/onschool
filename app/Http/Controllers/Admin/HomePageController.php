<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomePageController extends Controller
{
    private object $model;
    private string $title;
    private string $role = 'admin';

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();

        View::share('title', ucfirst($this->table));
        View::share('table', $this->table);
        View::share('role', $this->role);
    }

    public function index()
    {
        return view("$this->role.index");
    }
}
