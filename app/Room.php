<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model 
{
    protected $table = 'reservable';

    protected $primaryKey = 'id_reservable';

    /**
     * Get the reservations that belongs to the room.
     */
    public function reservations()
    {
        return $this->belongsToMany('App\Reservation');
    }
}
