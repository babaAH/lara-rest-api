<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /** Table name */
    protected $table = 'rooms';

        
    /**
     * bookings
     *
     * @return void
     */
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }

    /**
     * fillable
     * 
     * Need laravel for mass assigment
     * 
     * @var array
     */
    protected $fillable = [
        'active', 'description', 'price'
    ];
    
    /**
     * scopeIsActive
     *
     *  return only active models
     * 
     * @param  mixed $query
     * @return void
     */
    public function scopeIsActive($query)
    {
        return $query->where('active', true);
    }
}
