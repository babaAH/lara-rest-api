<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Models\Room as RoomModel;
use \App\Models\Booking as BookingModel;

class Booking extends Controller
{    
    private $resp = [
        "error" => false,
    ];
    private $status = 200;

    /**
     * Отрефакторить:
     * 1.? FormRequest
     * 2. вынести валидацию в отдельный метод
     */

    /**
     * Display \App\Models\Bookings by Room
     *
     * @param  mixed $req
     * @return json
     */
    public function index(Request $req)
    {
        $val = $this->makeIndexValidation($data = $req->all());

        if($val->passes()){
            $room = RoomModel::findOrFail($data['id']);

            $bookings = $room->whereHas('rooms', function($query){
                return $query->paginate(12);
            });

            $resp["bookings"] = $bookings;
        }else{
            $this->resp["error"] = true;
            $this->resp["msg"] = $val->messages();
            $this->status = 400;
        }

        return response()->json(
            $this->resp, $this->status
        );
    }

    /**
     * Отрефакторить
     * Вместо @param \Illuminate\Http\Request сделать @param \Illuminate\Http\BookingStoreRequest
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $val = $this->makeStoreValidation($data);

        if($val->passes()){
            $room = RoomModel::findOrFail($data['room_id']);
            
            $booking = BookingModel::create([
                'start_at' => $data['start_at'],
                'end_at'   => $data['end_at'],
            ]);

            $room->bookings()->save($booking);
        }else{
            $this->resp["error"] = true;
            $this->resp["msg"] = $val->messages();
            $this->status = 400;
        }

        return response()->json($this->resp, $this->status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = BookingModel::findOrFail($id);
        $booking->delete();
    }
    
    /**
     * makeIndexValidation
     *
     * @param  array $data
     * @return instance \Validator 
     */
    private function makeIndexValidation($data)
    {
        return \Validator::make($data,[
            'id' => 'required|integer',
        ]);
    }

    private function makeStoreValidation($data)
    {
        return \Validator::make($data,[
            'room_id'  => 'required|integer',
            'start_at' => 'required|date_format:Y-m-d',
            'end_at'   => 'required|date_format:Y-m-d'
        ]);
    }
}
