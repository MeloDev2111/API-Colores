<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Http\Request;
use App\Models\Color;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use SimpleXMLElement;


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
                            ->orderBy("updated_at","DESC")
                            ->orderBy("created_at","DESC")
                            ->paginate(
                                $perPage = $n_items,
                                //This parameter it's not working
                                $columns  = $metadata_to_show,
                                $pageName = "page"
                            );


        //Solution for columns parameter not working in method Paginate
        $selectedData = [];
        $selectedData["colors"] = $pagination->transform(function($item){
            //adding # to color hex code
            $item["color"] = "#".$item["color"];
            return $item;
        });
        // Recreate because transform removed pagination properties
        // metadata selected to show;
        $selectedData["current_page"] = $pagination->currentPage();
        $selectedData["next_page"] = $pagination->nextPageUrl();
        $selectedData["previous_page"] = $pagination->previousPageUrl();
        $selectedData["last_page"] = $pagination->lastPage();
        $selectedData["per_page"] = $pagination->perPage();
        $selectedData["total"] = $pagination->total();

        if(\request()->getContentType() == "xml"){
            $data = json_decode(json_encode($selectedData), true);
            $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><data></data>');
            // helper function call to convert array to xml
            $xml_data = array_to_xml($selectedData,$xml_data, "colors");
            // 206: Partial content
            return response($xml_data->asXML(),206)->header("Content-type","text/xml");
        }

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
        $validator = $this->get_color_validator(request()->all());

        // 400 Bad Request
        if($validator->fails()){return response()->json(["errors" => $validator->errors()->all(),] ,400);}

        // Formatting request to save in bd
        $dataRequest= $this->formatting_request($validator->validated());

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
        if ($color == null) {return response()->json([], 404);}
        $color->color = "#".$color->color;

        if(\request()->getContentType() == "xml"){
            $data = json_decode(json_encode($color->toArray()), true);
            $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><color></color>');
            // helper function call to convert array to xml
            $xml_data = array_to_xml($data,$xml_data);
            // 200 OK
            return response($xml_data->asXML(),200)->header("Content-type","text/xml");
        }

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
        //example: {"name":"El Rojito","color":"#FF0000","pantone_value":"15-1234","year":"2022"}
        $validator = $this->get_color_validator(request()->all());

        // 400 Bad Request
        if($validator->fails()){return response()->json(["errors" => $validator->errors()->all(),] ,400);}

        // Formatting request to save in bd
        $dataRequest= $this->formatting_request($validator->validated());

        // response
        $dataColor=[
            'id' => $id,
            'name'=>$dataRequest['name'],
            'color'=>$dataRequest['color'],
            'pantone_value'=>$dataRequest['pantone_value'],
            'year'=>$dataRequest['year'],
        ];

        // try update
        try {
            $color_to_update = Color::where("id","=", $id)->first();
            if ($color_to_update === null) {return response()->json(["error"=>"Not Found"], 404);}
            $color_to_update->update($dataColor);
            // 200 OK
            return response()->json($color_to_update, 200);
        } catch (\Throwable $th) {
            // 500 Internal Server Error
            return response()->json(null, 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $color_to_destroy = Color::where("id","=", $id)->first();
            if ($color_to_destroy === null) {return response()->json(["error"=>"Not Found"], 404);}
            $color_to_destroy->delete();
            // 204 No Content (en-US)
            return response()->json(null, 204);
        }catch (\Throwable $th) {
            // 500 Internal Server Error
            return response()->json(["error"=> "Internal Server Error".$th], 500);
        }
    }

    private function get_color_validator($request): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($request, [
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
    }

    private function formatting_request($data){
        // Validating default values [year, pantone]

        // default valur for year = actual year
        if(!isset($data["year"])){
            $year = date('Y', time());
            $data["year"] = $year;
        }

        // structure for possible feature, calculate the most close pantone value to the hex color
        if(!isset($data["pantone_value"])){
            // possible feature to implement
            // $dataRequest["pantone_value"] = get_pantone_code($dataRequest['color']);
            // If implement this: drop required validation from get_color_validator method
            // Implement the method get_pantone_code in helpers.
        }

        // removing # from color hex code if exist
        $color = $data['color'];
        $data['color'] = $color[0] == "#" ? substr($color, 1) : $color;
        $data['color'] = strtolower($data['color']);

        return $data;
    }
}
