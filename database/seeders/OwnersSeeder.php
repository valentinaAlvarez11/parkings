<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OwnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->delete();

        Owner::create(
            [
                'name' => 'Lina',
                'birthday' => '1981-04-20',
                'parking_id' => 1,
            ],
        );

        Owner::create(
            [
                'name' => 'Pablo',
                'birthday' => '2003-10-21',
                'parking_id' => 2,
            ],
        );

    }
}
