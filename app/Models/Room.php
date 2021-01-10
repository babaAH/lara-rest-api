<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   schema="Room",
 *   type="object",
 * )
 */
class Room extends Model
{
    /**
     * 
     *  @OA\Property(
     *      property="active",
     *      type="boolean"
     *  ),
     *  @OA\Property(
     *      property="description",
     *      type="string"
     *  ),
     *  @OA\Property(
     *      property="price",
     *      type="integer"
     *  )
     * )
     */
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
