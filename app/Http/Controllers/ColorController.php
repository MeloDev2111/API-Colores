<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Boolean;

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
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:100',
            'color' => ['required',
                            function ($attribute, $value, $fail) {
                                $msg = is_hexcode($value);
                                if ($msg != '' and gettype($msg) != "boolean") {
                                    $fail('The '.$attribute.' '.$msg);
                                }
                            },
                        ],
            'pantone_value' => ['required', 'max:7', 'regex:/^[0-9]{2}-[0-9]{4}/'],
            'year' => 'max:4'
        ]);

        if($validator->fails()){return response()->json(["errors" => $validator->errors()->all(),] ,400);}

        // Validating default values [year, pantone]
        $dataRequest=$validator->validated();

        if(!isset($dataRequest["year"])){
            $year = date('Y', time());
            $dataRequest["year"] = $year;
        }

        if(!isset($dataRequest["pantone_value"])){
            // possible feature to implement
            // $dataRequest["pantone_value"] = get_pantone_code($dataRequest['color']);
            return response()->json(["errors"=>"missing pantone value",],400);
        }

        // removing # from color hex code if exist
        $color = $dataRequest['color'];
        $dataRequest['color'] = $color[0] == "#" ? substr($color, 1) : $color;
        $dataRequest['color'] = strtolower($dataRequest['color']);

        // response
        $dataColor=[
            'name'=>$dataRequest['name'],
            'color'=>$dataRequest['color'],
            'pantone_value'=>$dataRequest['pantone_value'],
            'year'=>$dataRequest['year'],
        ];

        // try insert
        try {
            $insertColor = Color::create($dataColor);
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
