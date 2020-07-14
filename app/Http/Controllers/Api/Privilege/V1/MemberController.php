<?php


namespace App\Http\Controllers\Api\Privilege\V1;


use App\Http\Controllers\Controller;
use App\Ultilities\Privilege\V1\MemberUltility;
use Illuminate\Http\Request;


class MemberController extends Controller
{
    public function change( Request $request )
    {

        $arr = [
            'id' => $request->get('id',0),
            'name' => $request->get('name',''),
            'phone' => $request->get('phone',''),
            'address' => $request->get('address',''),
            'email' => $request->get('email',''),
            'enable' => $request->get('enable',''),
            'role' => $request->get('role',''),
            'password' => $request->get('password',''),

        ];

        $responseMsg = MemberUltility::changeMember( $arr );

        return $this->apiResponseResult($responseMsg);
    }


    /**
     * 修改当前登录用户信息
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function msgUpdate( Request $request , $id )
    {
        $arr = [
          'phone' => $request->get('phone',''),
          'address' => $request->get('address',''),
          'wechat' => $request->get('wechat',''),
        ];

        $response = MemberUltility::updateMsg( $id, $arr);

        return $this->apiResponseResult( $response );

    }


    public function password( Request $request , $id )
    {
        $response = MemberUltility::password($id,[
            'old_pwd' => $request->get('old_pwd'),
            'new_pwd' => $request->get('new_pwd'),
        ]);

        return $this->apiResponseResult( $response );
    }



    public function image( Request $request , $id )
    {
        $response = MemberUltility::image($id,[
            'image' => $request->get('image'),
        ]);

        return $this->apiResponseResult( $response );
    }



}
