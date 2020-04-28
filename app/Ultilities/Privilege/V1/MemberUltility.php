<?php
namespace App\Ultilities\Privilege\V1;

use App\Model\Privilege\Ability;
use App\Model\Privilege\Role;
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
    public static function getAllMembers( string $orderby = 'desc' , int $page = 1 , int $limit = 30 )
    {
        $roles = Role::pluck('name','id');
        $all = User::orderBy('id',$orderby)->paginate( $limit, ['*'] , 'page' , $page )->toArray();

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
            $member['roles'] = $temp;
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


    public static function getAbilityByRoleid(  int $id )
    {
        $result = ['action'=>[],'menu'=>[]];

        $all = Ability::where('role_id',$id)->get()->groupBy('type')->toArray();

        // 菜单
        if ( isset( $all[1] ) )
        {
            foreach ( $all[1] as $ability  )
            {
                $result['menu'][] = $ability['ability'];
            }
        }


        // 功能
        if ( isset( $all[2] ) )
        {
            foreach ( $all[2] as $ability  )
            {
                $result['action'][] = $ability['ability'];
            }
        }


        return $result;
    }




}
