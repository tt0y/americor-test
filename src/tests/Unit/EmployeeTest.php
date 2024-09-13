<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_employee()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $company = Company::factory()->create();

        $response = $this->postJson('/api/employees', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'company_id' => $company->id,
            'phone' => '1234567890',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('employees', ['email' => 'john.doe@example.com']);
    }

    /** @test */
    public function it_can_get_an_employee()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/employees/{$employee->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $employee->id,
                'first_name' => $employee->first_name,
            ]);
    }

    /** @test */
    public function it_can_update_an_employee()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $employee = Employee::factory()->create();

        $response = $this->putJson("/api/employees/{$employee->id}", [
            'first_name' => 'Jane',
            'last_name' => $employee->last_name,
            'email' => $employee->email,
            'company_id' => $employee->company_id,
            'phone' => $employee->phone,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('employees', ['first_name' => 'Jane']);
    }

    /** @test */
    public function it_can_delete_an_employee()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        // Create a company and an employee
        $company = Company::factory()->create();
        $employee = Employee::factory()->create(['company_id' => $company->id]);

        // Run DELETE employee request
        $response = $this->deleteJson("/api/employees/{$employee->id}");

        // Check if the response is 204 (No Content)
        $response->assertStatus(204);

        // Check if the employee is deleted from the database
        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
