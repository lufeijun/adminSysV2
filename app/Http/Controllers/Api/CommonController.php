<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    //
    public function uploadImage( Request $request )
    {
        $toPath = public_path().'/sys_images';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = \Ramsey\Uuid\Uuid::uuid1()->toString();
            $imgName = $fileName.'.'.$file->getClientOriginalExtension();
            $file->move( $toPath , $imgName );
            return $imgName;
        } else {
            return '0';
        }
    }

}
