<?php


namespace GraphApp\QueryFields\Privilege;


use App\Ultilities\Privilege\V1\MemberUltility;
use GraphApp\AppContext;
use GraphApp\Types\Privilege\SearchType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PrivilegeQueryField
{
    public function export()
    {
        return [
            'description' => '权限部分路由',
            'type' => new ObjectType([
                'description' => '权限部分',
                'name' => 'PrivilegeQueryField',
                'fields' => [
                    'user_list' => [
                        'description' => '用户列表',
                        'type' => \GraphApp\Types\FenyeListType::getInstance( \GraphApp\Types\Privilege\UserType::getInstance() ),
                        'resolve' => function ($root, $cell, AppContext $context) {
                            return MemberUltility::getAllMembers( $context->allArgs['search'] ,$context->allArgs['page'] );
                        }
                    ],



                    'role_list' => [
                        'description' => '角色列表',
                        'type' => Type::listOf( \GraphApp\Types\Privilege\RoleType::getInstance() ),
                        'resolve' => function ($root, $cell, AppContext $context) {
                            return MemberUltility::getAllRoles();
                        }
                    ],
                    'role_msg' => [
                        'description' => '角色信息',
                        'type' => \GraphApp\Types\Privilege\RoleType::getInstance(),
                        'resolve' => function ($root, $cell, AppContext $context) {
                            return MemberUltility::getRoleByid( $context->allArgs['id'] );
                        }
                    ],
                    'ability_action_tree' => [
                        'description' => '功能权限',
                        'type' => Type::string(),
                        'resolve' => function ($root, $cell, AppContext $context) {
                            return MemberUltility::allAbilitys('功能');
                        }
                    ],
                    'ability_menu_tree' => [
                        'description' => '菜单权限',
                        'type' => Type::string(),
                        'resolve' => function ($root, $cell, AppContext $context) {
                            return MemberUltility::allAbilitys('菜单');
                        }
                    ],

                ]
            ]),
            'args' => [
                'id' => [
                    'type' => Type::int(),
                    'description' => '客户id',
                ],
                'page' => [
                    'type' => Type::int(),
                    'description' => "页数",
                    'defaultValue' => 1,
                ],
                'search' => [
                    'type' => SearchType::getInstance(),
                    'description' => "查询字段"
                ],


            ],
            'resolve' => function ($root, $args, AppContext $context) {
                $context->allArgs = array_merge($args);
                return [];
            }
        ];
    }

}
