<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    
    /** Table name */
    protected $table = 'bookings';
    
    /**
     * get related \App\Models\Room
     *
     */
    public function room()
    {
        return $this->belongsTo(\App\Models\Room::class);
    }

    /**
     * fillable
     * 
     * Need laravel for mass assigment
     * 
     * @var array
     */
    protected $fillable = [
        'start_at', 'end_at'
    ];
}
