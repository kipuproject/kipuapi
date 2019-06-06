<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model 
{
    protected $table = 'reservation';

    protected $primaryKey = 'id_reserva';

    /**
     * Get the rooms associated to the reservation.
     */
    public function rooms()
    {
        return $this->belongsToMany('App\Room', 'reservation_reservable', 'id_reserva', 'id_reservable');
    }
}
