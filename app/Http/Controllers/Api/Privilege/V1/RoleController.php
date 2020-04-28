<?php


namespace App\Http\Controllers\Api\Privilege\V1;


use App\Http\Controllers\Controller;
use App\Model\Privilege\Ability;
use App\Ultilities\Privilege\V1\MemberUltility;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function list( Request $request )
    {
        $roles = MemberUltility::getAllRoles();
        return $this->apiResponse(0,'success',['roles'=>$roles]);
    }


    public function change( Request $request )
    {

        $arr = [
            'id' => $request->get('id',0),
            'name' => $request->get('name',''),
        ];

        $responseMsg = MemberUltility::changeRole( $arr );

        return $this->apiResponseResult($responseMsg);
    }



    public function permitGet( $id )
    {
        $menu = $this->_dealForTree( config("custom.ability.menuTree") );
        $action = $this->_dealForTree( config("custom.ability.actionTree") );

        $ability = MemberUltility::getAbilityByRoleid( $id );
        $role = MemberUltility::getRoleByid( $id );

        return $this->apiResponse(0,'success', ['menu'=>$menu,'action'=>$action,'ability'=>$ability,'role_name'=>$role->name]);
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



    /**
     * 为 element 树形插件构造数据
     * @param array $data
     * @return array
     */
    private function _dealForTree( array $data )
    {
        $result = [];
        $have = []; // 标志数组，防止重复
        foreach ( $data as $menu )
        {
            $tempStr = '';
            foreach ( explode(',',$menu) as $m )
            {
                $key = $tempStr . $m . ',';
                if ( ! in_array( $key , $have ) )
                {
                    $result[] = [
                        'label' => $m,
                        'parent' => $tempStr,
                        'key' =>  $key,
                    ];
                    $have[] = $key;
                }
                $tempStr .= $m.',';
            }
        }

        $data = array_column($result, null, 'key');
        // 树形结构的开始
        $tree = [];
        foreach ($data as $key => $val) {
            if ($val['parent'] == '') {
                $tree[] = &$data[$key];
            } else {
                $data[$val['parent']]['children'][] = &$data[$key];
            }
        }

        return $tree;
    }

}
