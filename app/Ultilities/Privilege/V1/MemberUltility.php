<?php
namespace App\Ultilities\Privilege\V1;

use App\Model\Privilege\Ability;
use App\Model\Privilege\Role;
use App\Tools\Json;
use App\User;
use Illuminate\Support\Facades\Hash;


/**
 *
 */
class MemberUltility
{

    /**
     * 通过角色 ids 获取角色名称
     */
    public static function getRolesByids( array $ids)
    {
        return Role::whereIn("id",$ids)->get()->toArray();
    }

    public static function getRoleByid( int $id)
    {
        return Role::find( $id );
    }


    /**
     * @param array $ids 角色id
     * @param int $type  类型 1 菜单权限 ， 2 功能权限
     * @return array
     */
    public static function getAbilityByRoleids( array $ids , int $type) :array
    {
        return Ability::whereIn('role_id',$ids)->where('type',$type)->distinct()->pluck('ability')->toArray();
    }

    /**
     * @return mixed
     */
    public static function getAllRoles()
    {
        return Role::get();
    }



    /**
     * 获取所有客户
     * @param string $orderby 排序
     * @param int $page 页数
     * @param int $limit 每页个数
     * @return
     */
    public static function getAllMembers( $search ,int $page = 1 , string $orderby = 'desc' , int $limit = 30 )
    {


        $roles = Role::pluck('name','id');
        $all = User::where("id","!=",0);

        // 在职
        if ( $search['enable'] != '全部' ) {
            $all = $all->where('enable', $search['enable'] == '在职' ? 1 : 0 );
        }

        // 角色
        if ( $search['role_id'] ) {
            $all = $all->where('role', 'like' , '%,'. $search['role_id'] .',%' );
        }

        // 关键字
        if ( $search['keyword'] ) {
            $keyword = $search['keyword'];
            $all = $all->where(function ( $query ) use( $keyword ){
                $query->where('name', 'like' , '%'. $keyword .'%' )
                    ->orWhere('phone', 'like' , '%'. $keyword .'%')
                    ->orWhere('email', 'like' , '%'. $keyword .'%');
            });
        }

        $all = $all->orderBy('id',$orderby)->paginate( $limit, ['*'] , 'page' , $page )->toArray();



        foreach ( $all['data']  as &$member) {
            $temp = [];
            foreach ( explode(',',$member['role']) as $rid )
            {
                if ( $rid && isset( $roles[$rid] ) )
                {
                    $temp[] = $roles[$rid];
                }
            }
            $member['enable'] = $member['enable']?'在职':'离职';
            $member['role_names'] = $temp;
        }
        unset( $member );

        return $all;
    }

    /**
     * @param array $arr
     * @return string
     */
    public static function changeMember( array $arr )
    {
        $have = User::where("email",$arr['email']);
        if ( $arr['id'] ) {
            $obj = User::find( $arr['id'] );
            $pwd = $arr['password'];

            $have = $have->where('id','!=',$arr['id']);
        } else {
            $obj = new User;
            $pwd = $arr['password']?:'123456789';
        }

        if ( $have->count() )
        {
            return '邮箱不能重复';
        }

        $obj->name = $arr['name'];
        $obj->email = $arr['email'];
        $obj->phone = $arr['phone'];
        $obj->address = $arr['address'];
        $obj->email = $arr['email'];
        $obj->enable = $arr['enable'] == '在职'?1:0;

        if ( $pwd ) {
            $obj->password = Hash::make($pwd);
        }

        $roles = Role::pluck('id','name');

        $obj->role = ',';
        foreach ( explode(',', $arr['role'] ) as $roleName ) {
            if ( isset( $roles[$roleName] ) )
            {
                $obj->role .= $roles[$roleName].',';
            }
        }

        $obj->save();

        return '';
    }


    public static function updateMsg( $id , $arr )
    {
        $user = User::find( $id );

        if ( ! $user )
        {
            return '未找到用户';
        }

        $user->phone = $arr['phone'];
        $user->address = $arr['address'];
        $user->wechat = $arr['wechat'];
        $user->save();

        return '';
    }


    public static function password( $id , $arr )
    {
        $user = User::find( $id );
        if ( ! $user )
        {
            return '未找到用户';
        }


        // 验证密码
        if ( ! password_verify($arr['old_pwd'] , $user->password) )
        {
            return '密码错误';
        }

        $user->password = Hash::make($arr['new_pwd']);
        $user->save();

        return '';

    }


    public static function image( $id , $arr )
    {
        $user = User::find( $id );
        if ( ! $user )
        {
            return '未找到用户';
        }

        $user->image = $arr['image'];
        $user->save();

        return '';
    }


    /**
     * 编辑|创建 角色
     * @param array $arr
     * @return string
     */
    public static function changeRole( array $arr)
    {
        $have = Role::where("name",$arr['name']);
        if ( $arr['id'] ) {
            $obj = Role::find( $arr['id'] );
            $have =  $have->where("id",'!=',$arr['id']);
        } else {
            $obj = new Role;
        }

        if ( $have->count() )
        {
            return '名称不能重复';
        }

        $obj->name = $arr['name'];
        $obj->save();

        return '';
    }


    /**
     * @param int $id
     * @param string $type 权限类型 1 菜单，2 功能
     * @return array
     */
    public static function getAbilityByRoleid(  int $id , string $type )
    {
        $typeInt = 0;
        switch ( $type ) {
            case '菜单':
                $typeInt = 1;
                break;
            case '功能' :
                $typeInt = 2;
                break;
        }


        $result = [];

        $all = Ability::where('role_id',$id)->where('type',$typeInt)->get()->toArray();

        foreach ( $all as $ability  )
        {
            $result[] = $ability['ability'];
        }

        return $result;
    }

    /**
     * @param string $type 权限类型 1 菜单，2 功能
     * @return array
     */
    public static function allAbilitys( string $type)
    {
        $data = [];

        switch ( $type ) {
            case '菜单':
                $data = config("custom.ability.menuTree");
                break;
            case '功能' :
                $data = config("custom.ability.actionTree");
                break;
        }

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

        return Json::arrayToJson($tree);
    }




}
