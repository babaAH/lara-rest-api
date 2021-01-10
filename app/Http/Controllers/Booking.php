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
     * @OA\Get(
     *     path="/api/booking",
     *     summary="Получение списка броней номера отеля",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="room_id",
     *         in="query",
     *         description="ID комнаты",
     *         required=true,
     *     ), 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Booking")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request",
     *     ),
     * )
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
            $room = RoomModel::findOrFail($data['room_id']);

            $bookings = $room->bookings()
                ->orderBy('start_at')
                ->paginate(12)
                ->toArray();

            $this->resp["bookings"] = $bookings;
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
     * @OA\Post(
     *     path="/api/booking",
     *     summary="Создание брони",
     *     tags={"Booking"},
     *     @OA\RequestBody(
     *         description="Параметры для создания брони",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Booking"),
     *     ),
    *      @OA\Parameter(
    *          name="room_id",
    *          in="query",
    *          description="ID комнаты",
    *          required=true,
    *      ), 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 ref="#/components/schemas/Booking")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request",
     *     ),
     * )
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
            $this->resp["booking_id"] = $booking->id;
        }else{
            $this->resp["error"] = true;
            $this->resp["msg"] = $val->messages();
            $this->status = 400;
        }

        return response()->json($this->resp, $this->status);
    }

    /**
     * @OA\Delete(
     *     path="/api/booking/{id}",
     *     summary="Удаление брони",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID брони",
     *         required=true,
     *     ), 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Booking")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request",
     *     ),
     * )
     */
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
        return response()->json(
            ["success" => true]
        );
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
            'room_id' => 'required|integer',
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
