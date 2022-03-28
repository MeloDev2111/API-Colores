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

# XML Generator
if (! function_exists('array_to_xml')) {
    function array_to_xml( $data, &$xml_data, $default_tag = "item") {
        foreach( $data as $key => $value ) {
            if( is_array($value) or is_object($value) ) {
                if( is_numeric($key) ){
                    $key = $default_tag; //dealing with <0/>..<n/> issues
                }
                $valueParsed = is_object($value)? json_decode(json_encode($value), true) :$value;
                $sub_node = $xml_data->addChild($key);
                array_to_xml($valueParsed, $sub_node);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
        }
        return $xml_data;
    }
}
