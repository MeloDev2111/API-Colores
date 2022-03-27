<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    // editable columns
    protected $fillable = ["name", "color", "pantone_value", "year"];

    // validate hex_code
    public static function is_hexcode($code){
        if(strlen($code) < 6 or strlen($code) >7){return false;}
        if(strlen($code) == 7 and !str_contains($code, "#")){return false;}
        if(strlen($code) == 6 and str_contains($code, "#")){return false;}
        if(preg_match("/[^0-9A-Fa-f#]/", $code)){return false;}
        if(preg_match_all("/#/", $code) > 1){return false;}
        return true;
    }
}
