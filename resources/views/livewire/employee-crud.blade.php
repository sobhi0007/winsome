<div class="container py-4 card p-5 shadow rounded">

    <div class="row">
        <div class="col-6">
            <h2 class="mb-4">Employee Management</h2>
        </div>
        <div class="col-6 text-end">
            <div class="row">

                <div class="col-6 text-end">
                    @if(count($selectedEmployees))
                    <button wire:click="deleteSelected" class="btn btn-danger">Delete Selected ({{
                        count($selectedEmployees)
                        }})</button>
                    @endif
                </div>
                <div class="col-6 ">
                    <button wire:click="openModal" class="btn btn-primary mb-3 ">
                        + Add Employee
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-2 mb-2">
            <input wire:model.live.500ms="search" type="text" class="form-control" placeholder="Search by name">
        </div>
        <div class="col-md-2 mb-2">
            <select wire:model.live="filterStatus" class="form-control">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <input type="date" wire:model.live="filterHiredDate" class="form-control" placeholder="Hired at">
        </div>

        <div class="col-md-2 mb-2">
            <button wire:click="toggleTrashed" class="btn btn-outline-secondary w-100">
                {{ $showTrashed ? 'Back to Active' : 'Deleted Employees' }}
            </button>
        </div>

        @if($showTrashed && $trashedCount > 0)
        <div class="col-md-2 mb-2">
            <button wire:click="restoreAll" class="btn btn-success w-100">
                Restore All
            </button>
        </div>
        @endif


    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" wire:model.live="selectAll"></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Position</th>
                    <th>Salary</th>
                    <th>Hired At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $employee)
                <tr>
                    <td><input type="checkbox" wire:model.live="selectedEmployees" value="{{ $employee->id }}"></td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->phone }}</td>
                    <td>{{ $employee->position }}</td>
                    <td><b>EGP</b> {{ $employee->salary }}</td>
                    <td>{{ $employee->hired_at }}</td>
                    <td>
                        <span
                            class="badge rounded-pill bg-{{ $employee->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="#" wire:click="edit({{ $employee->id }})" class="me-1"><i
                                class="fas fa-edit text-primary"></i></a>
                        <a href="#" wire:click="confirmDelete({{ $employee->id }})" class=""><i
                                class="fas fa-trash-alt text-danger"></i></a>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">No employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>


    <!-- Modal -->
    <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent="save" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @csrf

                    <div class="mb-2">
                        <label>Name</label>
                        <input type="text" class="form-control" wire:model.defer="name">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" class="form-control" wire:model.defer="email">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label>Phone</label>
                        <input type="text" class="form-control" wire:model.defer="phone">
                        @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label>Position</label>
                        <input type="text" class="form-control" wire:model.defer="position">
                        @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label>Salary</label>
                        <input type="number" class="form-control" wire:model.defer="salary" step="0.01">
                        @error('salary') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label>Hired At</label>
                        <input type="date" class="form-control" wire:model.defer="hired_at">
                        @error('hired_at') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-2">
                        <label>Status</label>
                        <select class="form-control" wire:model.defer="status">
                            <option value="">Select</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.addEventListener('openModal', () => {
        const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
        modal.show();
    });

    window.addEventListener('closeModal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('employeeModal'));
        modal.hide();
    });

  window.addEventListener('alert', event => {
        Swal.fire({
            icon: event.detail[0].type,
            text: event.detail[0].message,
            timer: 2000,
            showConfirmButton: false,
        });
    });


    window.addEventListener('confirmDelete', event => {
         const id = event.detail[0].id;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(id);
                  Livewire.dispatch('employee-crud-delete', { employeeId: id }); 
     
            }else{
                Swal.fire(
                    'Cancelled',
                    'Your employee is safe :)',
                    'error'
                )
            }
        });
    });
</script>
@endpush