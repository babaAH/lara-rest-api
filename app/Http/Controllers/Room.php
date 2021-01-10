<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use \App\Http\Requests\Room\RoomCreateRequest;

use \App\Models\Room as RoomModel;
use \App\Models\Booking as BookingModel;

class Room extends Controller
{
    private $resp = [
        "error" => false
    ];
    private $status = 200;

    
    /**
     * @OA\Get(
     *     path="/api/room",
     *     summary="Получение списка номеров",
     *     tags={"Room"},
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="По какому столбцу производить сортировку",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="Производить сортировку по возрастанию или убыванию",
     *         required=false,
     *     ), 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Room")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request",
     *     ),
     * )
     */
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

        $this->resp["rooms"] = $rooms;

        return response()->json($this->resp, $this->status);
    }

    /**
     * @OA\Post(
     *     path="/api/room",
     *     summary="Создание номера",
     *     tags={"Room"},
     *     @OA\RequestBody(
     *         description="Параметры для создания комнаты",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Room"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Room")
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
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        $val = $this->makeStoreValidation($data);

        if($val->passes()){
            $room = RoomModel::create([
                'active'      => $data['active'],
                'price'       => $data['price'],
                'description' => $data['description'],
            ]);

            $this->resp["room_id"] = $room->id;
        }else{
            $this->resp["error"] = true;
            $this->resp["msg"]   = $val->messages();
            $this->status        = 400;
        }



        return response()->json($this->resp, $this->status);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/room/{id}",
     *     summary="Удаление номера",
     *     tags={"Room"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID номера",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ), 
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Room")
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
        $room = RoomModel::findOrFail($id);
        $room->delete();
        return response()->json($this->resp, $this->status);
    }
    
    /**
     * get column by ordering
     *
     * @return string
     */
    private function getOrderColumn()
    {
        $val = \Validator::make(['order' => \Request::get("order"),],
            [
                'order' => 'in:price,created_at',
            ]
        );

        $val->passes() ? $columnName = \Request::get("order") : $columnName = 'created_at';

        return $columnName;
    }
    
    /**
     * get Direction Value for sorting
     *
     * @return string
     */
    private function getDirectionValue()
    {
        $val = \Validator::make(['direction' => \Request::get("direction")],
            [
                'direction' => 'in:asc,desc'
            ]
        );

        $val->passes() ? $directionValue = \Request::get("direction") : $directionValue = 'asc';
        
        return $directionValue;

    }

    private function makeStoreValidation(Array $data)
    {
        return \Validator::make($data, [
            'price'       => 'required|integer|between:0, 18446744073709551615', // 18446744073709551615  - max UNSIGNED INT in MySQL
            'description' => 'required|string|min:25|max:8191',
            'active'      => 'required|boolean',
        ]);
    }
}
