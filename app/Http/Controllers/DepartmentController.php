<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        // dd($departments);
        return DepartmentResource::collection($departments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:departments'],
            'code' => ['required', 'string', 'unique:departments'],
        ]);

        $department = Department::create($request->all());
        return new DepartmentResource($department);
    }


    public function show(Department $department)
    {
        return new DepartmentResource($department);
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => ['sometimes', 'string', 'unique:departments,name,' . $department->id],
            'code' => ['sometimes', 'string', 'unique:departments,code,' . $department->id],
        ]);

        $department->update($request->all());
        return new DepartmentResource($department);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(['message' => 'Department deleted successfully']);
    }
}
