<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicles')->delete();

        Vehicle::create([
            'license_plate' => "MJS07D",
            'color' => "Plata",
            'type_id' => "1",
            'owner_id' => "1",
        ]);

        Vehicle::create([
            'license_plate' => "NAL638",
            'color' => "Verde",
            'type_id' => "2",
            'owner_id' => "2",
        ]);

    }
}
