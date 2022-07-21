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
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'roleuser' => 1
        ]);

        User::create([
            'name' => 'Ujang',
            'email' => 'ujang@gmail.com',
            'password' => bcrypt('admin123'),
            'roleuser' => 2
        ]);
    }
}
