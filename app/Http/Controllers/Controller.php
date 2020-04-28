<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function apiResponse($status = 0 , $message = 'success' , $data = null)
    {
        if ( is_null( $data ) ) {
            $data = new \StdClass;
        }
        return response()->json(['status' => $status, 'message'=>$message ,'values' => $data], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function apiResponseResult( $msg )
    {
        if ( $msg ) {
            return $this->apiResponse(1,$msg);
        } else {
            return $this->apiResponse();
        }
    }


}
