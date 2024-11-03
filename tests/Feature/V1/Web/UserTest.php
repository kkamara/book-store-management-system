<?php

namespace Tests\Unit\V1\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Contracts\Console\Kernel;
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (!RefreshDatabaseState::$migrated) {
            $this->artisan(
                'migrate:fresh',
                array_merge(
                    $this->migrateFreshUsing(),
                    [
                        "--path" => "database/migrations/v1"
                    ],
                )
            );

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
    
    public function testAccountNameChange()
    {
        $email = $this->faker->unique()->safeEmail;
        $user = User::factory()->create(['email' => $email,]);
        $newName = "Jane Sarah Doe";
        Sanctum::actingAs(
            $user,
        );
        $response = $this->patchJson(
            '/api/web/user/account',
            [
                "name" => $newName,
                "password" => "secret",
                "password_confirmation" => "secret",
            ],
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath("data.id", $user->id)
            ->assertJsonPath("data.name", $newName);
    }
}
