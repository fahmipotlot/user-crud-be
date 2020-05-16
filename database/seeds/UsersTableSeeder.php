<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@localhost.com'
            ],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@localhost.com',
                'password' => bcrypt('admin'),
                'api_token' => str_random(100)
            ]
        );
    }
}
