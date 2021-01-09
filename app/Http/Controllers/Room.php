<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use \App\Http\Requests\Room\RoomCreateRequest;

use \App\Models\Room as RoomModel;
use \App\Models\Booking as BookingModel;

class Room extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $column = $this->getOrderColumn();
        $direction = $this->getDirectionValue();

        $rooms = RoomModel::isActive()
            ->orderBy($column, $direction)
            ->paginate(12)
            ->toArray();

        return response()->json(
            $rooms
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        $val = $this->makeStoreValidation($data);

        $room = RoomModel::create([
            'active'      => $data['active'],
            'price'       => $data['price'],
            'description' => $data['description'],
        ]);


        return response()->json([
            'error' => false,
            'roomId' => $room->id, 
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = RoomModel::find($id);

        $this->deleteCoupliedBookings($room->bookings() ?? []);

        $room->active = false;
        $room->save();

        return response()->json([
            "error" => false,
        ]);
    }
        
    /**
     * deleteCoupliedBookings
     *
     * @param  mixed $bookingsArr
     * @return void
     */
    private function deleteCoupliedBookings($bookingsArr)
    {
        foreach($bookingsArr as $booking){
            $booking->delete();
            $booking->save();
        }
    }

    private function getOrderColumn()
    {
        $val = \Validator::make([
            'order' => \Request::get("order"), 
        ],
        [
            'order' => 'in:price,created_at',
        ]);

        $val->passes() ? $columnName = \Request::get("order") : $columnName = 'created_at';

        return $columnName;
    }

    private function getDirectionValue()
    {
        
        $val = \Validator::make([
            'direction' => \Request::get("direction")
        ],
        [
            'direction' => 'in:asc,desc'
        ]);

        $val->passes() ? $directionValue = \Request::get("direction") : $directionValue = 'asc';
        
        return $directionValue;

    }
}
