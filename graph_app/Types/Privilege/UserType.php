<?php


namespace GraphApp\Types\Privilege;


use GraphApp\Types\BaseType;
use GraphQL\Type\Definition\Type;

class UserType extends BaseType
{
    public function __construct()
    {
        $config = [
            'description' => '用户信息',
            'name' => 'PrivilegeUserType',
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
                    'email' => [
                        'description' => '邮箱',
                        'type' => Type::string(),
                    ],
                    'phone' => [
                        'description' => '手机号',
                        'type' => Type::string(),
                    ],
                    'address' => [
                        'description' => '地址',
                        'type' => Type::string(),
                    ],
                    'enable' => [
                        'description' => '是否在职',
                        'type' => Type::string(),
                    ],
                    'role' => [
                        'description' => '角色id',
                        'type' => Type::string(),
                    ],
                    'role_names' => [
                        'description' => '角色名称',
                        'type' => Type::listOf( Type::string() ),
                    ],
                    'image' => [
                        'description' => '头像',
                        'type' => Type::string(),
                    ],



                ];
            }
        ];
        parent::__construct($config);
    }
}
