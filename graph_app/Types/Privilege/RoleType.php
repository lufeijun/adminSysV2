<?php


namespace GraphApp\Types\Privilege;


use App\Ultilities\Privilege\V1\MemberUltility;
use GraphApp\Types\BaseType;
use GraphQL\Type\Definition\Type;

class RoleType extends BaseType
{
    public function __construct()
    {
        $config = [
            'description' => '角色信息',
            'name' => 'PrivilegeRoleType',
            'fields' => function() {
                return [
                    'id' => [
                        'description' => 'id',
                        'type' => Type::int(),
                    ],
                    'name' => [
                        'description' => '角色名称',
                        'type' => Type::string(),
                    ],
                    'ability_action' => [
                        'description' => '用户功能权限',
                        'type' => Type::listOf( Type::string() ),
                        'resolve' => function($root, $args, $context){
                            return MemberUltility::getAbilityByRoleid( $root->id , '功能' );
                        },
                    ],
                    'ability_menu' => [
                        'description' => '用户菜单权限',
                        'type' => Type::listOf( Type::string() ),
                        'resolve' => function($root, $args, $context){
                            return MemberUltility::getAbilityByRoleid( $root->id , '菜单' );
                        },
                    ],


                ];
            }
        ];
        parent::__construct($config);
    }
}
