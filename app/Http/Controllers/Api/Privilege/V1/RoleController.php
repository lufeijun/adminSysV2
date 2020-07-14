<?php


namespace App\Http\Controllers\Api\Privilege\V1;


use App\Http\Controllers\Controller;
use App\Model\Privilege\Ability;
use App\Ultilities\Privilege\V1\MemberUltility;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function change( Request $request )
    {
        $arr = [
            'id' => $request->get('id',0),
            'name' => $request->get('name',''),
        ];

        $responseMsg = MemberUltility::changeRole( $arr );
        return $this->apiResponseResult($responseMsg);
    }


    public function permitUpdate(Request $request,$id)
    {
        // 功能权限
        $action = $request->get('action',[]);
        $actionDelete = [];

        \DB::beginTransaction();
        foreach ( $action as $ability )
        {
            Ability::firstOrCreate(['role_id'=>$id,'ability'=>$ability,'type'=>2]);
            $actionDelete[] = $action;
        }
        Ability::where('role_id',$id)->where('type',2)->whereNotIn('ability',$actionDelete)->delete();



        $menu = $request->get('menu',[]);
        $menuDelete = [];
        foreach ( $menu as $ability )
        {
            Ability::firstOrCreate(['role_id'=>$id,'ability'=>$ability,'type'=>1]);
            $menuDelete[] = $ability;
        }
        Ability::where('role_id',$id)->where('type',1)->whereNotIn('ability',$menuDelete)->delete();

        \DB::commit();

        return  $this->apiResponseResult('');

    }

}
