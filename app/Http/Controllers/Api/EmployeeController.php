<?php

namespace App\Http\Controllers\Api;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employee::withTrashed()->latest()->paginate(10);

            return ApiResponse::success([
                'employees' => EmployeeResource::collection($employees),
                'pagination' => [
                    'total' => $employees->total(),
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                ],
            ], 'Employees fetched successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to fetch employees', 500);
        }
    }

    public function store(EmployeeRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();
                $request->image->storeAs('employees', $imageName, 'public');
                $data['image'] = 'employees/' . $imageName;
            }

            $employee = Employee::create($data);

            return ApiResponse::success(new EmployeeResource($employee), 'Employee created successfully', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create employee', 500);
        }
    }


    public function show(Employee $employee)
    {
        try {
            return ApiResponse::success(new EmployeeResource($employee), 'Employee details', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Employee not found', 404);
        }
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        try {
            $employee->update($request->validated());

            return ApiResponse::success(new EmployeeResource($employee), 'Employee updated successfully', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update employee', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::withTrashed()->findOrFail($id);

            $employee->delete();

            return ApiResponse::success(null, 'Employee soft deleted', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Employee not found', 404);
        }
    }

    public function restore($id)
    {
        try {
            $employee = Employee::onlyTrashed()->findOrFail($id);
            $employee->restore();

            return ApiResponse::success(new EmployeeResource($employee), 'Employee restored successfully', 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Employee not found or already restored', 404);
        } catch (\Exception $e) {
            return ApiResponse::error('Something went wrong while restoring employee', 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = explode(',', $request->input('ids'));

            if (empty($ids)) {
                return ApiResponse::error('No IDs provided', 422);
            }

            $deleted = Employee::whereIn('id', $ids)->delete();

            if ($deleted === 0) {
                return ApiResponse::error('No employees found for deletion', 404);
            }

            return ApiResponse::success(null, 'Selected employees deleted', 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete employees', 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        $user = auth()->user();
        $token = $user->createToken('api-token')->plainTextToken;

        return ApiResponse::success([
            'user' => $user,
            'token' => $token,
        ], 'Login successful', 200);
    }

    public function trashed()
    {
        $trashedEmployees = Employee::onlyTrashed()->latest()->paginate(10);

        return ApiResponse::success([
            'employees' => EmployeeResource::collection($trashedEmployees),
            'pagination' => [
                'total' => $trashedEmployees->total(),
                'current_page' => $trashedEmployees->currentPage(),
                'last_page' => $trashedEmployees->lastPage(),
            ],
        ], 'Trashed employees fetched successfully', 200);
    }
}
