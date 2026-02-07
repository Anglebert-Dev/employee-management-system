<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): string
    {
        $user = User::factory()->create();

        return $user->createToken('test-token')->plainTextToken;
    }

    public function test_can_crud_employees(): void
    {
        $token = $this->authenticate();

        $create = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/employees', [
                'names' => 'John Doe',
                'email' => 'john@example.com',
                'employee_identifier' => 'EMP-001',
                'phone_number' => '1234567890',
            ]);

        $create->assertCreated()->assertJsonFragment([
            'message' => 'Employee created successfully',
            'names' => 'John Doe',
        ]);

        $employeeId = $create['data']['id'];

        $index = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/employees');

        $index->assertOk()->assertJsonFragment(['names' => 'John Doe']);

        $show = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson("/api/employees/{$employeeId}");

        $show->assertOk()->assertJsonFragment(['id' => $employeeId]);

        $update = $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson("/api/employees/{$employeeId}", [
                'names' => 'John Smith',
            ]);

        $update->assertOk()->assertJsonFragment(['names' => 'John Smith']);

        $delete = $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson("/api/employees/{$employeeId}");

        $delete->assertOk()->assertJsonFragment(['message' => 'Employee deleted successfully']);

        $this->assertDatabaseMissing(Employee::class, ['id' => $employeeId]);
    }

    public function test_returns_clean_404_json(): void
    {
        $token = $this->authenticate();

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/employees/9999');

        $response->assertNotFound()->assertJson([
            'message' => 'The requested resource was not found.',
            'status' => 404
        ]);
    }
}
