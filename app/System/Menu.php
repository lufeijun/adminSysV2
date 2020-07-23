<?php
namespace App\System;


use App\Ultilities\Privilege\V1\MemberUltility;
use Config;

/**
 *
 */
class Menu
{

    /**
    * 获取最上边的第一大模块
    */
    public static function getFirstMenuName()
    {
        $module = '';
        $path = explode('/',\Request::path());
        if ( count( $path ) < 2 ) {
            $module;
        } else {
            $menuFirst = config('custom.menu.first');
            $firstMenuKey = $path[1];
            if ( isset( $menuFirst[$firstMenuKey] ) ) {
               $module = $menuFirst[$firstMenuKey];
            }
        }
        return $module;
    }


    /**
     * 检测菜单权限，
     * 横向菜单为一级权限
     * 左侧分别为二级、三级权限
     * @param $menu
     * @param $level
     * @param bool $enableJump
     * @return bool
     * @throws \App\Exceptions\PrivilegeException
     */
    public static function checkMenuGranted($menu,$level,$enableJump = false)
    {
        if ( $level == 'three' )
        {
            $menu .= ',';
        }

        $menuArr = Admin::getLoginedMessage('menu_'.$level.'_granted',[]);
        $enable = isset( $menuArr[$menu] );
        if ($enableJump && ! $enable) {
            throw new \App\Exceptions\PrivilegeException("无操作权限");
        }
        return $enable;
    }


    public static function checkActionGranted($action,$enableJump = false)
    {
        $action .= ',';
        $menuArr = Admin::getLoginedMessage('action_granted',[]);
        $enable = in_array($action, $menuArr);
        if ($enableJump && ! $enable) {
            throw new \App\Exceptions\PrivilegeException("无操作权限");
        }
        return $enable;
    }


    /**
     * 判断是否为当前URL，用来控制是否选中左侧材料栏
     * @param $url
     * @param $containUrl
     * @return bool
     */
    public static function isCurrentUrl($url,$containUrl)
    {
        foreach ($containUrl as $contain) {
            if ( strpos($url,$contain) !== false ) {
                return true;
            }
        }
        return false;
    }




    /**
     * 初始化菜单需要的session参数
     */
    public static function initSession()
    {
        $user = \Auth::user();
        $data = [];
        $roleIds = explode(',', $user->role);
        $data['id'] = $user->id;
        $data['name'] = $user->name;
        $data['phone'] = $user->phone;
        $data['address'] = $user->address;
        $data['email'] = $user->email;
        $data['image'] = $user->image;
        $data['role'] = $user->role;
        $data['email'] = $user->email;
        $data['enable'] = $user->enable;
        $data['roles'] = array_column( MemberUltility::getRolesByids( $roleIds )  , 'name');
        $data['action_granted'] = MemberUltility::getAbilityByRoleids($roleIds,2);

        // 处理菜单权限，
        $data['menu_first_granted'] = [];
        $data['menu_second_granted'] = [];
        $data['menu_three_granted'] = MemberUltility::getAbilityByRoleids($roleIds,1); // 左侧二级
        foreach ($data['menu_three_granted'] as $menu_granted) {
            $menu_arr = explode(',', $menu_granted);
            $first  = $menu_arr[0];
            $second = $menu_arr[0].','.$menu_arr[1];
            $data['menu_first_granted'][$first] = 1;
            $data['menu_second_granted'][$second] = 1;
        }

        $data['menu_three_granted'] = array_flip($data['menu_three_granted']);

        session(['administrator'=>$data]);

        self::dealFirstMenu($data);

    }

    /**
     * @param array $data
     */
    public static function dealFirstMenu(array $data)
    {
        self::dealMenuOrder($data);
        $firstMenuGranted = $data['menu_first_granted'];
        $threeMenuGranted = $data['menu_three_granted'];
        $firstDefaultMenu = [];
        $menu = config('custom.menu.menu');
        foreach ($firstMenuGranted as $firstMenuName => $firstMenuUrl) {
            if ( isset( $menu[$firstMenuName] ) ) {
                foreach ($menu[$firstMenuName] as $secondMenuKey =>$secondMenu) {
                    foreach ($secondMenu['threeMenu'] as $threeMenu) {
                        if ( isset( $threeMenuGranted[$threeMenu['check'].','] ) ) {
                            $firstDefaultMenu = $threeMenu['url'];
                            break 2;
                        }
                    }
                }
                session(['administrator.menu_first_granted.'.$firstMenuName=>$firstDefaultMenu]);
            }
        }

    }

    public static function dealMenuOrder(array $data)
    {
        $menus = config('custom.menu.menu');
        $firstGranted = $data['menu_first_granted'];
        $firstGrantedInOrder = []; // 调整顺序后的一级菜单数组
        foreach ($menus as $menuKey => $menuValue) {
            if ( isset($firstGranted[$menuKey]) ) {
                $firstGrantedInOrder[$menuKey] = $firstGranted[$menuKey];
            }
        }
        session(['administrator.menu_first_granted'=>$firstGrantedInOrder]);
    }


    public static function getHtmlTitle()
    {
       $notIn = [
            'login',
            'admin',
            'admin/add',
            'soft-versions',
            'customer/map',
        ];
        $title = '';

        $first = $second = $third = env('APP_NAME');

        $url = \Request::path();

        // 登录和登录之后的首页
        if ( in_array( $url ,  $notIn ) ) {
            return ;
        }

        $urlArr = explode("/", $url );

        // 如果不是 admin 开头的，不处理
        if ( $urlArr[0] != 'admin' ) {
            return ;
        }

        // 一级菜单
        if ( isset( Config::get('custom.menu.first')[$urlArr[1]] ) ) {
            $first = Config::get('custom.menu.first')[$urlArr[1]];
        }


        $menus = Config::get('custom.menu.menu');

        if ( isset( $menus[$first] ) ) {

            // 二级菜单
            foreach ( $menus[$first] as $secondName => $secondMenu ) {

                // 三级菜单
                foreach ($secondMenu['threeMenu'] as $threeMenu) {

                    foreach ($threeMenu['current'] as $current) {
                        if ( strpos($url,$current) !== false ) {
                            $second = $secondName;
                            $third  = $threeMenu['name'];
                            break 2;
                        }
                    }
                }

            }
        }

        $final = $first . '_' . $second . '_' . $third . '_';
        return $final;

    }

}



