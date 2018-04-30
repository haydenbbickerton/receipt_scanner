<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class)->create([
            'password'  => \Illuminate\Support\Facades\Hash::make('password123'),
            'username'  => 'admin',
            'email'     => 'admin@gmail.com',
            'role'      => \App\Models\User::ADMIN_ROLE,
            'isActive'  => 1,
        ]);

        factory(App\Models\User::class, 5)->create();
    }
}
