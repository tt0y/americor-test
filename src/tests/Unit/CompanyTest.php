<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_company()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/companies', [
            'name' => 'Test Company',
            'email' => 'test@company.com',
            'website' => 'http://testcompany.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('companies', ['name' => 'Test Company']);
    }

    /** @test */
    public function it_can_get_a_company()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $company = Company::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $company->id,
                'name' => $company->name,
            ]);
    }

    /** @test */
    public function it_can_update_a_company()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $company = Company::factory()->create();

        $response = $this->putJson("/api/companies/{$company->id}", [
            'name' => 'Updated Company Name',
            'email' => $company->email,
            'website' => $company->website,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('companies', ['name' => 'Updated Company Name']);
    }

    /** @test */
    public function it_can_delete_a_company()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
