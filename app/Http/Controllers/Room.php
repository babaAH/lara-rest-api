<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Http\Requests\Room\RoomCreateRequest;

use \App\Models\Room as RoomModel;

class Room extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = RoomModel::isActive()->paginate(12)->toArray();
        return response()->json(
            $rooms
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Room\RoomCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        $data = $request->all();

        $room = RoomModel::findOrFail($id);

        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = RoomModel::find($id)->get();

        $room->active = false;
        $room->save();
    }
}