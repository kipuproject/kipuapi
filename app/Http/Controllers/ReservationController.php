<?php
namespace App\Http\Controllers;

use App\Reservation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    /**
     * Retrieve all reservations.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('update-post', $request->route('hotel_id'))) {
          //  abort(403);
        }

        $page    = $request->input('page', 1);
        $perPage = $request->input('perPage', 30);
        $sortBy  = $request->input('sortBy', 'id_reserva');
        $order   = $request->input('order', 'desc');
        $filterColumn = $request->input('column', 'desc');
        $filterValue  = $request->input('value', 'desc');

        $model = new Reservation();

        if($request->route('hotel_id') == 3) { //@TODO
            DB::setDefaultConnection('adobe');
        } 
        if($request->route('hotel_id') == 2) { //@TODO
            DB::setDefaultConnection('mastranto');
        }   

        $reservations = $model->with('rooms')->orderBy($sortBy, $order)->paginate($perPage, ['*'], 'page', $page);

        if($request->has('column') && $request->has('value')) {
            $reservations = $model->where($filterColumn, 'like', '%'. $filterValue . '%')
                                  ->orderBy($sortBy, $order)
                                  ->paginate($perPage, ['*'], 'page', $page);
        }
        $reservations->getCollection()
            ->transform(function($reservation) {
                $roomName = '';
                $adults   = '';
                $children  = '';
                $infants  = '';
                if(count($reservation->rooms) > 0) { 
                    $roomName = $reservation->rooms[0]->nombre;
                    $adults   = $reservation->rooms[0]->pivot->adults;
                    $children  = $reservation->rooms[0]->pivot->children;
                    $infants  = $reservation->rooms[0]->pivot->infants;
                }    
                return [
                    'reservation_id' => $reservation->id_reserva,
                    'room_name' => $roomName,
                    'check_in' => gmdate("Y-m-d", $reservation->fecha_inicio),
                    'check_out' => gmdate("Y-m-d", $reservation->fecha_fin),
                    'guest_name' => $reservation->guest_name,
                    'total' => $reservation->valor_total,
                    'paid' => $reservation->valor_pagado,
                    'source' => $reservation->medio,
                    'adults' => $adults,
                    'children' => $children,
                    'infants' => $infants,
                    'hotel_notes' => $reservation->observacion,
                    'guest_notes' => $reservation->observacion_cliente,
                    'created' => gmdate("Y-m-d\TH:i:s\Z", $reservation->fecha_registro),
                    'status' => $reservation->estado_reserva,
                    'payment_status' => $reservation->estado_pago
                ];
            })->toArray();

        return response()->json($reservations);
    }
}