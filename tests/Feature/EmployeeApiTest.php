<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $token = $this->user->createToken('api-token')->plainTextToken;

        $this->headers = ['Authorization' => "Bearer $token"];
    }

    public function test_can_fetch_employees()
    {
        Employee::factory()->count(5)->create();

        $response = $this->getJson('/api/employees', $this->headers);

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

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


    public function test_can_show_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employees/{$employee->id}", $this->headers);
        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_can_update_employee()
    {
        $employee = Employee::factory()->create();

        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/employees/{$employee->id}", array_merge($employee->toArray(), $data), $this->headers);
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_can_soft_delete_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/employees/{$employee->id}", [], $this->headers);
        $response->assertStatus(200);

        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
    }

    public function test_can_restore_soft_deleted_employee()
    {
        $employee = Employee::factory()->create();
        $employee->delete();

        $response = $this->postJson("/api/employees/restore/{$employee->id}", [], $this->headers);
        $response->assertStatus(200);

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'deleted_at' => null]);
    }

    public function test_can_bulk_delete_employees()
    {
        $employees = Employee::factory()->count(3)->create();

        $ids = $employees->pluck('id')->implode(',');
        $response = $this->postJson('/api/employees/bulk-delete', ['ids' => $ids], $this->headers);
        $response->assertStatus(200);

        foreach ($employees as $employee) {
            $this->assertSoftDeleted('employees', ['id' => $employee->id]);
        }
    }

    public function test_can_fetch_trashed_employees()
    {
        Employee::factory()->count(2)->create()->each->delete();

        $response = $this->getJson('/api/employees/deleted', $this->headers);
        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }
}
