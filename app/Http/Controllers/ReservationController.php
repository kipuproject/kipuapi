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
        $perPage = $request->input('perPage', 10);
        $sortBy  = $request->input('sortperPageBy', 'fecha_inicio');
        $order   = $request->input('order', 'desc');

        $model = new Reservation();

        if($request->route('hotel_id') == 3) { //@TODO
            DB::setDefaultConnection('adobe');
        } 
        if($request->route('hotel_id') == 2) { //@TODO
            DB::setDefaultConnection('mastranto');
        }   

        if($request->has('check_in')) {
            $filterValue  = explode(",",$request->input('check_in'));
            $checkinStart = explode("-",$filterValue[0]);
            $year   = $checkinStart[0];   
            $month  = $checkinStart[1];
            $day    = $checkinStart[2];
            $timeStampStart = mktime(0,0,0,$month,$day,$year);
            $checkinEnd = explode("-",$filterValue[1]);
            $year   = $checkinEnd[0];
            $month  = $checkinEnd[1];
            $day    = $checkinEnd[2];
            $timeStampEnd = mktime(0,0,0,$month,$day,$year);
            $timeStampEnd = $timeStampEnd + 86399;

            $model = $model->whereBetween('fecha_inicio', [$timeStampStart, $timeStampEnd]);
        }

        if($request->has('guest_name')) {
            $model = $model->where('guest_name', 'like', '%'. $request->input('guest_name') . '%');    
        }

        if($request->has('status')) {
            $model = $model->where('estado_reserva', '=', $request->input('status'));    
        }        

        $model = $model->orderBy($sortBy, $order);

        if(!$request->has('all')) {
            $reservations = $model->paginate($perPage, ['*'], 'page', $page);
        } else {
            $reservations = $model->get();
        }

        $bookingStatus = config('variable.bookingStatus');

        $reservations->transform(function($reservation) use ($bookingStatus) {
            $roomName = '';
            $adults   = '';
            $children = '';
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
                'balance' => ($reservation->valor_total - $reservation->valor_pagado),
                'source' => $reservation->medio,
                'adults' => $adults,
                'children' => $children,
                'infants' => $infants,
                'hotel_notes' => $reservation->observacion,
                'guest_notes' => $reservation->observacion_cliente,
                'created' => gmdate("Y-m-d\TH:i:s\Z", $reservation->fecha_registro),
                'status' => $bookingStatus[$reservation->estado_reserva],
                'payment_status' => $reservation->estado_pago
            ];
        })->toArray();

        return response()->json($reservations);
    }
}