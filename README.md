# 🧑‍💼 Employee Management System

A Laravel + Livewire powered Employee Management System with complete RESTful API, real-time UI, bulk actions, image upload, and automated tests. Built for performance, scalability, and ease of use.

---

## 📌 Table of Contents

1. [Project Features](#-project-features)
2. [Installation Guide](#-installation-guide)
3. [API Endpoints](#-api-endpoints)
4. [Livewire Features](#-livewire-features)
5. [Testing](#-testing)
6. [Useful Links](#-useful-links)
7. [License](#-license)

---

## 🚀 Project Features

- Full CRUD for Employees (Create, Read, Update, Delete)
- RESTful API with Laravel Sanctum authentication
- Image upload for employee photos
- Soft delete with restore & trash management
- Bulk actions (delete, restore)
- Advanced filtering & search
- Real-time UI with Livewire
- Fully responsive Bootstrap 5 UI
- Database seeding (10,000+ fake employees)
- API & Feature testing using PHPUnit

---

## 🛠️ Installation Guide

### Requirements

- PHP >= 8.1
- Composer
- MySQL or compatible database
- Node.js + npm (for Vite and frontend assets)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/sobhi0007/winsome.git
cd winsome

# 2. Install PHP dependencies
composer install

# 3. Create .env file
cp .env.example .env
php artisan key:generate

# 4. Set up your database in .env

# 5. Run migrations and seeders
php artisan migrate --seed

# 6. Install frontend assets (if needed)
npm install
npm run dev

# 7. Serve the project
php artisan serve
```

---

## 🔐 API Endpoints

All API routes require authentication via Sanctum.

### Authentication

| Method | Endpoint   | Description           |
| ------ | ---------- | --------------------- |
| POST   | /api/login | Login & get API token |

### Employee Endpoints

| Method | Endpoint                    | Description                   |
| ------ | --------------------------- | ----------------------------- |
| GET    | /api/employees              | List employees (with trashed) |
| POST   | /api/employees              | Create new employee           |
| GET    | /api/employees/{id}         | Get employee details          |
| PUT    | /api/employees/{id}         | Update employee               |
| DELETE | /api/employees/{id}         | Soft delete employee          |
| POST   | /api/employees/restore/{id} | Restore employee              |
| POST   | /api/employees/bulk-delete  | Bulk delete (IDs array)       |
| GET    | /api/employees/deleted      | List trashed employees        |

> All responses are formatted using a custom `ApiResponse` helper and `EmployeeResource`.

---

## ⚡ Livewire Features

- Component: `EmployeeCrud.php`
- Paginated listing, real-time search & filters
- Modal forms for create & update
- Soft delete and restore
- Bulk actions
- Responsive design using Bootstrap 5
- Integrated in `resources/views/home.blade.php`

---

## ✅ Testing

Run automated API tests using:

```bash
php artisan test
```

Test file: `tests/Feature/EmployeeApiTest.php`
Covers authentication, CRUD, image upload, bulk operations, soft delete & restore.

---

## 🔗 Useful Links

- 📁 GitHub Repository: [https://github.com/sobhi0007/winsome](https://github.com/sobhi0007/winsome)
- 📬 Postman API Documentation: [https://documenter.getpostman.com/view/26226662/2sB3B7MYRj](https://documenter.getpostman.com/view/26226662/2sB3B7MYRj)
- 🧪 Laravel Docs: [https://laravel.com/docs](https://laravel.com/docs)
- ⚡ Livewire Docs: [https://livewire.laravel.com](https://livewire.laravel.com)

---

---

## 📄 License

This project is open-sourced under the [MIT license](LICENSE).

---

> Made with ❤️ by Mohamed Sobhi
