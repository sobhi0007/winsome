<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:employees,email,' . $employeeId,
            'phone'     => 'nullable|string|max:15',
            'position'  => 'required|string',
            'salary'    => 'required|numeric|min:0',
            'hired_at'  => 'required|date',
            'status'    => 'required|in:active,inactive',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
