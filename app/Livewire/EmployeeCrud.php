<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Employee;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class EmployeeCrud extends Component
{
    use WithPagination;
    public $search = '';
    public $filterStatus = '';
    public $filterHiredDate = null;
    public $selectedEmployees = [];
    public $selectAll = false;
    public $showTrashed = false;
    public $trashedCount = 0;

    public $name, $email, $phone, $position, $salary, $hired_at, $status;
    public $employee_id;
    public $modalTitle = 'Add Employee';
    protected $paginationTheme = 'bootstrap';
    protected $rules = [
        'name' => 'required|max:100',
        'email' => 'required|email|unique:employees,email',
        'phone' => 'nullable|max:15',
        'position' => 'required',
        'salary' => 'required|numeric|min:0',
        'hired_at' => 'required|date',
        'status' => 'required|in:active,inactive',
    ];

    public function render()
    {
        $query = Employee::query();

        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterHiredDate) {
            $query->whereDate('hired_at', $this->filterHiredDate);
        }

        $this->trashedCount = Employee::onlyTrashed()->count();

        return view('livewire.employee-crud', [
            'employees' => $query->latest()->paginate(10),
        ]);
    }



    public function resetInputFields()
    {
        $this->reset(['name', 'email', 'phone', 'position', 'salary', 'hired_at', 'status', 'employee_id']);
        $this->resetValidation();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->modalTitle = 'Add Employee';
        $this->dispatch('openModal');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $this->employee_id = $employee->id;
        $this->name = $employee->name;
        $this->email = $employee->email;
        $this->phone = $employee->phone;
        $this->position = $employee->position;
        $this->salary = $employee->salary;
        $this->hired_at = $employee->hired_at;
        $this->status = $employee->status;

        $this->modalTitle = 'Edit Employee';
        $this->dispatch('openModal');
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->employee_id) {
            $rules['email'] = 'required|email|unique:employees,email,' . $this->employee_id;
        }
        $this->validate($rules);

        Employee::updateOrCreate(['id' => $this->employee_id], [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->position,
            'salary' => $this->salary,
            'hired_at' => $this->hired_at,
            'status' => $this->status,
        ]);

        $this->dispatch('closeModal');
        $this->dispatch('alert', ['type' => 'success', 'message' => $this->employee_id ? 'Employee Updated' : 'Employee Created']);
        $this->resetInputFields();
    }

    public function confirmDelete($id)
    {
        $this->employee_id = $id;
        $this->dispatch('confirmDelete', ['id' => $id]);
    }
    
    #[On('employee-crud-delete')]
    public function delete($employeeId)
    {
        Employee::findOrFail($employeeId)->delete();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'Employee Deleted']);
        $this->resetInputFields();
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedEmployees = Employee::pluck('id')->toArray();
        } else {
            $this->selectedEmployees = [];
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterFromDate', 'filterToDate']);
    }

    public function deleteSelected()
    {

        if (count($this->selectedEmployees)) {
            Employee::whereIn('id', $this->selectedEmployees)->delete();
            $this->dispatch('alert', ['type' => 'success', 'message' => 'Selected employees deleted']);
            $this->reset(['selectedEmployees', 'selectAll']);
        }
    }

    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        $this->resetPage();
    }
    public function restoreAll()
    {
        Employee::onlyTrashed()->restore();
        $this->dispatch('alert', ['type' => 'success', 'message' => 'All deleted employees restored']);
    }
}
