<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        return view('admin.departments.index', [
            'departments' => Department::ordered()->when($q, fn ($w) => $w->where('name', 'like', "%$q%")->orWhere('note', 'like', "%$q%"))->get(),
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('admin.departments.form', ['department' => new Department()]);
    }

    private function rules(): array
    {
        return ['name' => ['required', 'string', 'max:120'], 'note' => ['nullable', 'string', 'max:500'], 'phone' => ['required', 'string', 'max:20']];
    }

    public function store(Request $request)
    {
        Department::create($request->validate($this->rules()));

        return redirect()->route('admin.departments.index')->with('status', 'Added. It now shows on the website.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.form', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $department->update($request->validate($this->rules()));

        return redirect()->route('admin.departments.index')->with('status', 'Saved.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return back()->with('status', 'Removed.');
    }
}
