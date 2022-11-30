<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->delete();

        Type::create([
            'vehicle_type' => "Carro",
            'tariff' => 3000,

        ]);

        Type::create([
            'vehicle_type' => "Moto",
            'tariff' => 1000,
        ]);


    }
}
