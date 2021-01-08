<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /** Table name */
    protected $table = 'rooms';

    /** Room - Booking 1 to Many relation func  */
    public function bookings()
    {
        return $this->hasMany(Bookings::class);
    }

    /** For mass assigment */
    protected $fillable = [
        'active', 'description', 'price'
    ];

    public function scopeIsActive($query)
    {
        return $query->where('active', true);
    }
}
