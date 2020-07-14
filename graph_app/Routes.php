<?php

namespace GraphApp;

class Routes
{
    public static function queries()
    {
        return [
            'privilege'  => \GraphApp\QueryFields\Privilege\PrivilegeQueryField::class,
        ];
    }
    public static function mutations()
    {
        return [

        ];
    }
}
