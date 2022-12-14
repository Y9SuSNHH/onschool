<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class StudentController extends Controller
{
    private object $model;
    private string $title;
    private string $role = 'admin';

    public function __construct()
    {
        $this->model = Student::query();
        $this->table = (new Student())->getTable();
        View::share('title', ucfirst($this->table));
        View::share('table', $this->table);
        View::share('role', $this->role);
    }

    public function index()
    {
        return view("$this->role.$this->table.index");
    }

    public function create()
    {
        return view("$this->role.$this->table.create");
    }

    public function edit()
    {
        return view("$this->role.$this->table.edit");
    }
}
