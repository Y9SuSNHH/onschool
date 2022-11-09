<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LogController extends Controller
{
    private string $table;
    private string $role = 'admin';

    public function __construct()
    {
        $this->table = 'logs';
        View::share('title', ucfirst($this->table));
        View::share('table', $this->table);
        View::share('role', $this->role);
    }

    public function index()
    {
        return view("$this->role.$this->table.index");
    }
}
