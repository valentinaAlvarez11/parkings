<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create(
            [
                'name' => 'Valentina',
                'email' => 'valentina@gmail.com',
                'password' => Hash::make('hola123'),

            ],
        );

        User::create(
            [
                'name' => 'Juan Esteban',
                'email' => 'juanes@gmail.com',
                'password' => Hash::make('hola123'),
            ],
        );

        User::create(
            [
                'name' => 'Juan Carlos',
                'email' => 'juanca@gmail.com',
                'password' => Hash::make('hola123'),
            ],
        );
    }
}
