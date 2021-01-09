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
     * Get related \App\Models\Booking
     */
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class);
    }
    
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
