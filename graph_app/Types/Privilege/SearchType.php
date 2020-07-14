<?php


namespace GraphApp\Types\Privilege;


use GraphApp\Types\BaseInputObjectType;
use GraphQL\Type\Definition\Type;

class SearchType extends BaseInputObjectType
{
    public function __construct()
    {
        $config = [
            'description' => '检索字段',
            'name' => 'PrivilegeSearch',
            'fields' => function() {
                return [
                    'enable' => [
                        'description' => '在职状态',
                        'type' => Type::string(),
                        'defaultValue' => '',
                    ],
                    'role_id' => [
                        'description' => '角色id',
                        'type' => Type::int(),
                        'defaultValue' => '',
                    ],
                    'keyword' => [
                        'description' => '关键词',
                        'type' => Type::string(),
                        'defaultValue' => '',
                    ],

                ];
            },

        ];
        parent::__construct($config);
    }
}
