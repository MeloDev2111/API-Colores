<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;


class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $n_items = 3;
        $props_to_show = ["id","name","color"];
        $metadata_to_show = ["data", "current_page","next_page_url","previous_page_url","last_page","per_page","total"];
        $pagination = Color::select($props_to_show)
                            ->orderBy("created_at","DESC")
                            ->orderBy("updated_at","DESC")
                            ->paginate(
                                $perPage = $n_items,
                                //This parameter it's not working
                                $columns  = $metadata_to_show,
                                $pageName = "page"
                            );


        //Solution for columns parameter not working in method Paginate
        $selectedData = [];
        $selectedData["colors"] = $pagination->transform(function($item){
            return $item;
        });
        // Recreate because transform removed pagination properties
        // metadata selected to show;
        $selectedData["current_page"] = $pagination->currentPage();
        $selectedData["next_page_url"] = $pagination->nextPageUrl();
        $selectedData["previous_page_url"] = $pagination->previousPageUrl();
        $selectedData["last_page"] = $pagination->lastPage();
        $selectedData["per_page"] = $pagination->perPage();
        $selectedData["total"] = $pagination->total();

        // 206: Partial content
        return response()->json($selectedData , 206);
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

        // 400 Bad Request
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
            // 201 Created
            return response()->json($insertColor, 201);
        } catch (\Throwable $th) {
            // 500 Internal Server Error
            return response()->json(null, 500);
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
        // 200 OK
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
        // 200 OK
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
        // 204 No Content (en-US)
        return respones()->json(null, 204);
    }
}
