# Employee Management Module Documentation

## Table of Contents

1. Introduction
2. Installation & Setup
3. Database Migration & Seeding
4. API Features & Endpoints
5. Livewire Features & UI
6. All Features Overview
7. File Structure & Roles
8. Unit Testing (EmployeeApiTest)
9. Troubleshooting & Notes

---

## 1. Introduction

This module is a Laravel-based employee management system supporting RESTful API and Livewire-powered UI. It provides CRUD operations, authentication, soft delete/restore, bulk actions, advanced filtering, and comprehensive automated testing for employee records.


### 🔗 Useful Links

- 📁 GitHub Repository: [https://github.com/sobhi0007/winsome](https://github.com/sobhi0007/winsome)
- 📬 Postman API Documentation: [https://documenter.getpostman.com/view/26226662/2sB3B7MYRj](https://documenter.getpostman.com/view/26226662/2sB3B7MYRj)

---

## 2. Installation & Setup

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL or compatible database

### Steps

1. Clone the repository:
   ```sh
   git clone https://github.com/sobhi0007/winsome
   cd employee-management-module
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Copy and configure environment:
   ```sh
   cp .env.example .env
   # Edit .env for DB and other settings
   php artisan key:generate
   ```
4. Install frontend dependencies (if using Vite):
   ```sh
   npm install
   npm run dev
   ```
5. Run migrations and seeders:
   ```sh
   php artisan migrate --seed
   ```

---

## 3. Database Migration & Seeding

- **Migration:** `database/migrations/2025_07_23_200918_create_employees_table.php`
  - Fields: id, name, email, phone, position, salary, hired_at, status, image, softDeletes, timestamps
  - Indexes: name, status, hired_at
- **Seeders:**
  - `UserSeeder.php`: Creates an admin user
  - `EmployeeSeeder.php`: Generates 10,000 employee records
  - `DatabaseSeeder.php`: Runs both seeders
- **Factory:**
  - `EmployeeFactory.php`: Defines fake data for employees

---

## 4. API Features & Endpoints

All API routes are defined in `routes/api.php` and handled by `app/Http/Controllers/Api/EmployeeController.php`.

### Authentication

- `POST /api/login` — Login and receive API token

### Protected Endpoints (require `auth:sanctum`)

- `GET /api/employees` — List employees (paginated, includes trashed)
- `POST /api/employees` — Create employee (supports image upload)
- `GET /api/employees/{id}` — Show employee details
- `PUT /api/employees/{id}` — Update employee
- `DELETE /api/employees/{id}` — Soft delete employee
- `POST /api/employees/restore/{id}` — Restore soft deleted employee
- `POST /api/employees/bulk-delete` — Bulk soft delete (comma-separated IDs)
- `GET /api/employees/deleted` — List trashed employees

### API Responses

- Standardized using `ApiResponse` helper (success/error, status codes, messages)
- Uses `EmployeeResource` for consistent employee data formatting

---

## 5. Livewire Features & UI

Livewire is used for dynamic, real-time UI in the browser.

### Main Component: `app/Livewire/EmployeeCrud.php`

- **Features:**
  - Paginated employee listing
  - Search, filter by status, filter by hired date
  - View and toggle trashed (soft deleted) employees
  - Create/edit employee via modal
  - Soft delete, restore, bulk delete
  - Real-time alerts and feedback
- **Properties:**
  - `search`, `filterStatus`, `filterHiredDate`, `showTrashed`, `trashedCount`, `selectedEmployees`, `selectAll`, etc.
- **Methods:**
  - `render()`, `save()`, `edit()`, `delete()`, `deleteSelected()`, `restoreAll()`, `resetFilters()`, etc.
- **View:**
  - Blade file: `resources/views/livewire/employee-crud.blade.php`
  - Integrated in `home.blade.php` via `@livewire('employee-crud')`

### UI Layout

- Main layout: `resources/views/layouts/app.blade.php` (Bootstrap 5, Vite, Livewire styles)
- Home page: `resources/views/home.blade.php` (mounts Livewire component)

---

## 6. All Features Overview

- Employee CRUD (Create, Read, Update, Delete)
- Soft delete and restore (single and bulk)
- Authentication (API token via Sanctum)
- Image upload for employees
- Search, filter, and pagination
- Bulk selection and actions
- Trashed employees management
- Real-time UI with Livewire
- Responsive design (Bootstrap 5)
- Automated feature and API testing

---

## 7. File Structure & Roles

- `app/Http/Controllers/Api/EmployeeController.php`: API logic
- `app/Livewire/EmployeeCrud.php`: Livewire UI logic
- `app/Models/Employee.php`: Employee model (with SoftDeletes)
- `database/migrations/2025_07_23_200918_create_employees_table.php`: Table schema
- `database/factories/EmployeeFactory.php`: Fake data for seeding
- `database/seeders/EmployeeSeeder.php`, `UserSeeder.php`, `DatabaseSeeder.php`: Seeders
- `resources/views/home.blade.php`, `layouts/app.blade.php`, `livewire/employee-crud.blade.php`: UI views
- `routes/api.php`, `routes/web.php`: Route definitions
- `composer.json`, `composer.lock`: Dependency management
- `app/Providers/AppServiceProvider.php`: Pagination theme setup
- `tests/Feature/EmployeeApiTest.php`: Automated API feature tests

---

## 8. Unit Testing (EmployeeApiTest)

Automated tests are provided in `tests/Feature/EmployeeApiTest.php` to ensure API reliability and correctness.

### Test Coverage

- **Authentication:** Sets up a user and API token for all requests
- **Fetch Employees:** Verifies paginated employee listing
- **Store Employee with Image:** Tests employee creation with image upload and storage
- **Show Employee:** Checks retrieval of a single employee
- **Update Employee:** Verifies updating employee details
- **Soft Delete Employee:** Ensures soft deletion works and is reflected in DB
- **Restore Employee:** Tests restoring a soft deleted employee
- **Bulk Delete Employees:** Verifies bulk soft deletion
- **Fetch Trashed Employees:** Checks listing of soft deleted employees

### Example Test Method

```php
public function test_can_store_employee_with_image()
{
    Storage::fake('public');
    $image = UploadedFile::fake()->image('photo.jpg');
    $data = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '01000000000',
        'position' => 'Developer',
        'salary' => 5000,
        'hired_at' => now()->toDateString(),
        'status' => 'active',
        'image' => $image
    ];
    $response = $this->postJson('/api/employees', $data, $this->headers);
    $response->assertStatus(201)
        ->assertJsonStructure(['status', 'message', 'data']);
    $imagePath = $response->json('data.image');
    Storage::disk('public')->assertExists($imagePath);
}
```

### Running Tests

To run all tests:

```sh
php artisan test
```

---

## 9. Troubleshooting & Notes

- Ensure all dependencies are installed via Composer and NPM
- Configure `.env` for correct database and app settings
- Use `php artisan migrate --seed` to set up database
- For PDF documentation, install Pandoc and run:
  ```sh
  pandoc EmployeeModuleDocumentation.md -o EmployeeModuleDocumentation.pdf
  ```
- For Livewire, ensure `livewire/livewire` is installed and configured
- For API, use tools like Postman to test endpoints
- For tests, use `php artisan test` and check results

---

For further details, refer to the source code and Laravel/Livewire documentation.
