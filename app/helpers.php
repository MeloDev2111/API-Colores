<?php

if (! function_exists('is_hexcode')) {
    // validate hex_code
    function is_hexcode($value){
        if(strlen($value) < 6 or strlen($value) >7){return 'has invalid length.';}
        if(strlen($value) == 7 and !str_contains($value, "#")){return 'has invalid length.';}
        if(strlen($value) == 6 and str_contains($value, "#")){return 'has invalid length.';}
        if(preg_match("/[^0-9A-Fa-f#]/", $value)){return 'is invalid.';}
        if(preg_match_all("/#/", $value) > 1){return 'has more than one hashtag.';}
        return true;
    }
}

# Possible feature to implement
if (! function_exists('get_pantone_code')) {
    function get_pantone_code($value)
    {
        return false;
    }
}
