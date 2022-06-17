<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Repository;
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

    public function test_index_empty()
    {
        Repository::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get("repositories")
            ->assertStatus(200)
            ->assertSee("No existen repositorios que mostrar");
    }

    public function test_index_withd_data()
    {
        $user = User::factory()->create();

        $repository = Repository::factory()->create(["user_id" => $user->id]);
        $this->actingAs($user)
            ->get("repositories")
            ->assertSee($repository->id)
            ->assertSee($repository->url);
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

    public function test_update()
    {
        $user = User::factory()->create();

        $repository = Repository::factory()->create(["user_id" => $user->id]);
        $data = [
            "url" => $this->faker->url(),
            "description" => $this->faker->text(),
        ];
        $this->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertRedirect("repositories/$repository->id/edit");
        $this->assertDatabaseHas("repositories", $data);
    }

    public function test_validate_store()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post("repositories", [])
            ->assertStatus(302)
            ->assertSessionHasErrors(["url", "description"]);
    }

    public function test_validate_update()
    {
        $repository = Repository::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user)
            ->put("repositories/$repository->id", [])
            ->assertStatus(302)
            ->assertSessionHasErrors(["url", "description"]);
    }

    public function test_destroy()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(["user_id" => $user->id]);
        $this->actingAs($user)
            ->delete(route("repositories.destroy", $repository))
            ->assertRedirect("repositories");

        $this->assertDatabaseMissing("repositories", [
            "id" => $repository->id,
            "url" => $repository->url,
            "description" => $repository->description,
        ]);
    }

    public function test_destroy_policy()
    {
        $repository = Repository::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)
            ->delete(route("repositories.destroy", $repository))
            ->assertStatus(403);
    }

    public function test_policy_update()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $data = [
            "url" => $this->faker->url(),
            "description" => $this->faker->text(),
        ];

        $this->actingAs($user)
            ->put("repositories/$repository->id", $data)
            ->assertStatus(403);
    }

    public function test_show_policy()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create();

        $this->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(403);
    }

    public function test_show()
    {
        $user = User::factory()->create();
        $repository = Repository::factory()->create(["user_id" => $user->id]);

        $this->actingAs($user)
            ->get("repositories/$repository->id")
            ->assertStatus(200);
    }
}
