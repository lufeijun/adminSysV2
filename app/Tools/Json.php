<?php


namespace App\Tools;


class Json
{
    public static function jsonToArray( $string )
    {
        $data = json_decode( $string , true );

        return is_array( $data ) ? $data : [];
    }


    public static function arrayToJson( $array )
    {
        if ( ! is_array( $array ) )
        {
            $array = [];
        }

        return json_encode( $array , JSON_UNESCAPED_UNICODE);
    }
}
