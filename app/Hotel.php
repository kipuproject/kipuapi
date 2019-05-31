<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model 
{
    protected $table = 'commerce';

    protected $primaryKey = 'id_commerce';

    /**
     * Get the users that owns the hotel.
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

}
