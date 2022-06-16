<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RespositoryControllerTest extends TestCase
{
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
}
