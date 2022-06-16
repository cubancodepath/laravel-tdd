<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RespositoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_guest()
    {
        $this->get("repositories")->assertRedirect("login");
        $this->get("repositories/1")->assertRedirect("login");
        $this->get("repositories/1/edit")->assertRedirect("login");
        $this->put("repositories/1")->assertRedirect("login");
        $this->put("repositories/create")->assertRedirect("login");
        $this->post("repositories", [])->assertRedirect("login");
        $this->delete("repositories/1")->assertRedirect("login");
    }

    public function test_store()
    {
        $data = [
            "url" => $this->faker->url(),
            "description" => $this->faker->text(),
        ];
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post("repositories", $data)
            ->assertRedirect("repositories");
        $this->assertDatabaseHas("repositories", $data);
    }
}
