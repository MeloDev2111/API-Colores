<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Http\Request;
use App\Models\Color;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // codigo 206, para resultados paginados
        return response()->json(Color::all(), 206);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //example: {"name":"El Rojito","color":"#FF0000","pantone_value":"15-1234","year":"2022"}
        $dataRequest=request()->all();

        $timezone = "America/Lima";
        date_default_timezone_set($timezone);
        $DateAndTime = date('Y-m-d H:i:s', time());

        $color = $dataRequest['color'];
        // validate color hex code
        if(!$this->is_hexcode($color)){return "error";}

        //remove # from color hex code
        $dataRequest['color'] = $color[0] == "#" ? substr($color, 1) : $color;

        $dataColor=[
            'name'=>$dataRequest['name'],
            'color'=>$dataRequest['color'],
            'pantone_value'=>$dataRequest['pantone_value'],
            'year'=>$dataRequest['year'],
        ];

        return response()->json($dataColor, 201);
        //
        try {
            $insertColor = Color::insert($dataColor);
            return response()->json($insertColor, 201);
        } catch (\Throwable $th) {
            return response()->json(null, 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $color = Color::find($id);
        return response()->json($color, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $color = $request->all();
        echo $color;
        return response()->json($color, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Color::delete($id);
        return respones()->json(null, 204);
    }
}
