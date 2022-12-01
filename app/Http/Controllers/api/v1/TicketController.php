<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\api\v1\TicketStoreRequest;
use App\Models\Parking;
use App\Models\User;
use App\Models\Parking_place;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::orderBy('id', 'asc')->get();
        return response()->json(['data' => $tickets], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketStoreRequest $request, Parking $parking)
    {
        $ticket = new Ticket();
        $ticket->parking_place_id = $request->input('parking_place_id');
        $parking_place_id = $request->input('parking_place_id');
        $parking_place = Parking_place::find($parking_place_id);
        $parking_place_parking_id = $parking_place->parking_id;
        $ticket_vehicle = $ticket->vehicle_id;



        if($parking_place_parking_id != 1){
            return response ()->json(['data' => 'lugar de parqueo no disponible'], 400);
        }else{
        $vehicle_id = $request->input('vehicle_id');
        $vehicle_park = DB::table('tickets')->where('vehicle_id', $vehicle_id)->exists();
        if($vehicle_park){
            return response()->json(['data' => 'el vehiculo ya fue parqueado'], 400);
        }else{
        $ticket->vehicle_id = $request->input('vehicle_id');
        $ticket->parking_id = $parking->id;
        $ticket->save();

        return response()->json(['data' => 'el vehiculo fue parqueado exitosamente'], 201);
        }
     }

    }


    public function unpark(Ticket $ticket)
    {
        $ticket->exit_time = now();
        return response()->json(['data' => $ticket], 200);
    }

    public function test_free_parking_space(){

        $this->seed();
        $user = User::find(1);
        $response = $this->actingAs($user)
        ->withHeaders(['Accept' => 'application/json','Content-Type' => 'application/json'])
        ->getJson("/api/v1/parkings/{parking}/parking_places?status=available")
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
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return response()->json(['data' => $ticket], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        // $ticket->update($request->all());
        $ticket->exit_time = now();
        $ticket->save();
        return response()->json(['data' => $ticket], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return response(null, 204);
    }

    public function free(Ticket $ticket)
    {
        $parkingsAvailable = DB::table('parking_places')
            ->where('parking_id', $parking->id)
            ->count('id');
        $ticketsUnavailable = DB::table('tickets')
            ->where('parking_id', $parking->id)
            ->where('exit_time', null)
            ->count('id');

        $parkingsAvailable = $parkingsAvailable - $ticketsUnavailable;

        if ($parkingsAvailable > 0) {
            return response()->json(['data' => 'lugar de parqueo disponible'], 200);
        } else {
            return response()->json(['data' => 'lugar de parqueo no disponible'], 400);
        }
    }

    public function estadisticas( Parking $parking)
    {
        // cuantas motos y cuantos autos hay en el estacionamiento, y cuantos lugares hay disponibles

        $carros = DB::table('tickets')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.id')
            ->where('tickets.parking_id', $parking->id)
            ->where('vehicles.type_id', '1')
            ->where('tickets.exit_time', null)
            ->count('tickets.id');
        $motos = DB::table('tickets')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.id')
            ->where('tickets.parking_id', $parking->id)
            ->where('vehicles.type_id', '2')
            ->where('tickets.exit_time', null)
            ->count('tickets.id');
        $total = $carros + $motos;
        return response()->json(['data' => ['total' => $total, 'carros' => $carros, 'motos' => $motos], 200]);
    }

    // count the total produced by the parking
    public function total(Parking $parking)
    {
        $carros = DB::table('tickets')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.id')
            ->where('tickets.parking_id', $parking->id)
            ->where('vehicles.type_id', '1')
            ->where('tickets.exit_time', null)
            ->count('tickets.id');
        $motos = DB::table('tickets')
            ->join('vehicles', 'tickets.vehicle_id', '=', 'vehicles.id')
            ->where('tickets.parking_id', $parking->id)
            ->where('vehicles.type_id', '2')
            ->where('tickets.exit_time', null)
            ->count('tickets.id');

        $total = ($carros * 3000) + ($motos * 1000);
        return response()->json(['data' => $total], 200);
    }
}
