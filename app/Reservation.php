<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model 
{
    protected $table = 'reservation';

    protected $primaryKey = 'id_reserva';

    public function clients()
    {
        $database = 'master';
        return $this->hasMany('App\User', "$database.user", 'id_usuario', 'id_usuario');
    }
}
