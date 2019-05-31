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
            abort(403);
        }

        $page    = $request->input('page', 1);
        $perPage = $request->input('perPage', 30);
        $sortBy  = $request->input('sortBy', 'id_reserva');
        $order   = $request->input('order', 'desc');
        $filterColumn = $request->input('column', 'desc');
        $filterValue  = $request->input('value', 'desc');

        $model = new Reservation();
        $model->setConnection('adobe');

        $reservations = $model->orderBy($sortBy, $order)->paginate($perPage, ['*'], 'page', $page);

        if($request->has('column') && $request->has('value')) {
            $reservations = $model->where($filterColumn, 'like', '%'. $filterValue . '%')
                                  ->orderBy($sortBy, $order)
                                  ->paginate($perPage, ['*'], 'page', $page);
        }

        $reservations->getCollection()
              ->transform(function($reservation) {
                  $user = User::find($reservation->cliente);
                  return [
                      'id_reserva' => $reservation->id_reserva,
                      'fecha_inicio' => gmdate("Y-m-d\TH:i:s\Z", $reservation->fecha_inicio),
                      'fecha_fin' => gmdate("Y-m-d\TH:i:s\Z", $reservation->fecha_fin),
                      'cliente' => $user->nombre.' '.$user->apellido,
                      'valor_total' => $reservation->valor_total,
                      'valor_pagado' => $reservation->valor_pagado,
                      'observacion' => $reservation->observacion,
                      'observacion_cliente' => $reservation->observacion_cliente,
                      'fecha_registro' => gmdate("Y-m-d\TH:i:s\Z", $reservation->fecha_registro),
                      'estado_reserva' => $reservation->estado_reserva,
                      'estado_pago' => $reservation->estado_pago,
                      'estado' => $reservation->estado
                  ];
              })->toArray();

        return $reservations;
    }
}