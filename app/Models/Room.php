<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    /** Table name */
    protected $table = 'rooms';

    public $hasMany = [
        'bookings' => [
            
        ]
    ];

    /** For mass assigment */
    protected $fillable = [
        'active', 'description', 'price'
    ];

    public function scopeIsActive($query)
    {
        return $query->where('active', true);
    }
}
