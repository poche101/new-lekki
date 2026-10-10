<?php

namespace App\Http\Controllers;

use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('pages.departments', ['departments' => Department::ordered()->get()]);
    }
}
