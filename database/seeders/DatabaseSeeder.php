<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $tenant = Tenant::query()->create(
            attributes: [
                'id' => 'test1'
            ]
        );
        $tenant->domains()->create(
            attributes: [
                'domain' => 'test1.localhost'
            ]
        );
        Tenant::all()->runForEach(function () {
            $user = User::factory()->create([
                'name' => 'hassan',
                "email" => 'a@a.com',
                'password' => 'Aa@123456',  
            ]);

            Post::factory()->create([
                "user_id"=>$user->id,
                'title' => 'title',
                "body" => 'body post',
            ]);
        });
    }
}
