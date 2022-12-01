<?php

namespace Database\Seeders;

use App\Models\Parking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParkingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('parkings')->delete();

        Parking::create([
            'name' => 'Parking #1',
            'address' => 'Cra 24 # 56-35',
            'user_id' => 1,
        ]);

        Parking::create([
            'name' => 'Parking #2',
            'address' => 'Cra 55 # 11-04',
            'user_id' => 2,
        ]);
        Parking::create([
            'name' => 'Parking #3',
            'address' => 'Cra 34 # 14-04',
            'user_id' => 2,
        ]);
        Parking::create([
            'name' => 'Parking #4',
            'address' => 'Cra 45 # 11-45',
            'user_id' => 2,
        ]);
    }
}
