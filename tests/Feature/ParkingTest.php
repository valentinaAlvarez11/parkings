<?php

namespace Tests\Feature;

use App\Models\Parking;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Vehicle;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
  //parquear en un lugar de parqueo disponible
    public function test_park_vehicle()
    {
        $vehicle_id= 1;
        $parking_place_id = 3;
        $id = 1;

        $user = User::find(1);
        $response = $this
                        ->actingAs($user)
                        ->postJson("/api/v1/parkings/$id/tickets",
                        [
                            'vehicle_id' =>$vehicle_id,
                            'parking_place_id' => $parking_place_id,
                        ])

                        ->assertStatus(201)
                        ->assertJson([
                            'data' => 'el vehiculo fue parqueado exitosamente'
                        ]);

                    $this->assertDatabaseHas('tickets',[
                        "vehicle_id"=>$vehicle_id,
                        "parking_place_id"=>$parking_place_id,
                    ]);

    }

    //parquear en un lugar de parqueo no disponible
    public function test_in_an_unavailable_space()
    {
        $vehicle_id= 1;
        $parking_place_id = 4;
        $id = 1;

        $user = User::find(1);
        $response = $this
                        ->actingAs($user)
                        ->postJson("/api/v1/parkings/$id/tickets",
                        [
                            'vehicle_id' =>$vehicle_id,
                            'parking_place_id' => $parking_place_id,
                        ])

                        ->assertStatus(400)
                        ->assertJson([
                            'data' => 'lugar de parqueo no disponible'
                        ]);

    }

   //parquear un vehiculo que ya fue parqueado
    public function test_park_a_vehicle_alredy_been_parking()
    {
        $vehicle_id= 1;
        $parking_place_id = 2;
        $id = 1;

        $user = User::find(1);
        $response = $this
                        ->actingAs($user)
                        ->postJson("/api/v1/parkings/$id/tickets",
                        [
                            'vehicle_id' =>$vehicle_id,
                            'parking_place_id' => $parking_place_id,
                        ])

                        ->assertStatus(400)
                        ->assertJson([
                            'data' => 'el vehiculo ya fue parqueado'
                        ]);
    }

    // desaparcar un vehiculo existente
    public function test_unpark_vehicle()
    {

        $this->seed();
        $ticket = Ticket:: first();
        $ticket_id = $ticket->id;

        $ticktes= Ticket::find($ticket_id);
        $vehicle_id = $ticktes->vehicle_id;

        $id = Parking::first ()->id;
        $user = User::first();
        $response = $this
                        ->actingAs($user)
                        ->putJson("/api/v1/parkings/$id/tickets/$ticket_id")
                        ->assertStatus(200)
                        ->assertJson([
                            'data' => 'el vehiculo ha sido desparqueado exitosamente'
                        ]);

        $new_ticket = Ticket::find($ticket_id);
        $this->assertNotEquals(null, $new_ticket->exit_time);
    }

    // desaparcar un vehiculo no existente
    public function test_unpark_no_existent_vehicle()
    {
        $vehicle_id= 1;
        $parking_place_id = 3;
        $id = 1;

        $user = User::find(1);
        $response = $this
            ->actingAs($user)
            ->postJson("/api/v1/parkings/$id/update",
            [
                'vehicle_id' =>$vehicle_id,
                'parking_place_id' => $parking_place_id,
            ])

            ->assertStatus(400)
            ->assertJson([
                'data' => 'el vehiculo que desea desparquear no existe'
            ]);
    }

//     // lugar de parqueo libre
    public function test_free_parking_space(){

         $this->seed();
         $user = User::find(1);
         $response = $this->actingAs($user)
         ->getJson("/api/v1/parkings/{parking}/parking_location=available")
         ->assertStatus(200)
         ->assertJsonStructure([
            "data" => [
                 'row',
                 'colum',
                 'parking_id'
             ]
         ])
         ->assertJson([
             "data"=>[

             ]
         ]);
     }

//    // cuando hay un parqueo libre
   public function test_parking_free()
    {
        $id = 1;

        $user = User::find(1);
        $response = $this
            ->actingAs($user)
            ->getJson("/api/v1/parkings/$id/free")

            ->assertStatus(200)
            ->assertJson([
                'data' => 'lugar de parqueo disponible'
            ]);
    }

//    llenar todos los parqueos y mirar si hay alguno libre
    public function test_parking_full()
    {
        $id = 1;

        $user = User::find(1);
        $response = $this
            ->actingAs($user)
            ->getJson("/api/v1/parkings/$id/free")

            ->assertStatus(400)
            ->assertJson([
                'data' => 'lugar de parqueo no disponible'
            ]);
    }


//    cuando no hay vehiculos parqueados
    public function test_parking_empty()
    {
        $id = 1;

        $user = User::find(1);
        $response = $this
            ->actingAs($user)
            ->getJson("/api/v1/parkings/$id/estadistica")

            ->assertStatus(400)
            ->assertJson([
                'data' => [
                    'total' => 0,
                    'carros' => 0,
                    'motos' => 0
                ]
            ]);
    }

}
