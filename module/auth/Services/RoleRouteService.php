<?php

namespace Modules\Auth\Services;

use Modules\Auth\Enums\RoleType;

class RoleRouteService
{
    public function getRoleAccess(string $name): ?array
    {

        // you can assess this controller after sign in
        // could has role or not
        $controllerRoles = array(

            'profile' => null,
            'dashboard' => null,
            'chart' => [RoleType::Admin, RoleType::Coworker],

            'chatContact' => [RoleType::Admin, RoleType::Coworker, RoleType::Blogger],
            'chatRoom' => [RoleType::Admin, RoleType::Coworker, RoleType::Blogger],
            'chatRoomMedia' => [RoleType::Admin, RoleType::Coworker, RoleType::Blogger],
            'chatPrivate' => [RoleType::Admin, RoleType::Coworker, RoleType::Blogger],
            'chatPrivateMedia' => [RoleType::Admin, RoleType::Coworker, RoleType::Blogger],


            'user' => [RoleType::Admin],
            'group' => [RoleType::Admin],
            'permission' => [RoleType::Admin],
            'usersPermission' => [RoleType::Admin],
            'groupsPermission' => [RoleType::Admin],

            'setting' => [RoleType::Admin],
            'visitor' => [RoleType::Admin, RoleType::Coworker],

            'advertisement' => [RoleType::Admin, RoleType::Coworker],
            'advertisementMedia' => [RoleType::Admin, RoleType::Coworker],

            'contact' => [RoleType::Admin, RoleType::Coworker],
            'contactMedia' => [RoleType::Admin, RoleType::Coworker],

            'newsCategory' => [RoleType::Admin, RoleType::Blogger],
            'newsSubCategory' => [RoleType::Admin, RoleType::Blogger],
            'newsPost' => [RoleType::Admin, RoleType::Blogger],
            'newsComment' => [RoleType::Admin, RoleType::Blogger],
            'newsMedia' => [RoleType::Admin, RoleType::Blogger],

            'viewOption' => [RoleType::Admin, RoleType::Coworker],
            'viewMedia' => [RoleType::Admin, RoleType::Coworker],

            'requestCategory' => [RoleType::Admin, RoleType::Coworker],
            'requestPost' => [RoleType::Admin, RoleType::Coworker],
            'requestReply' => [RoleType::Admin, RoleType::Coworker],
        );

        foreach ($controllerRoles as $key => $value) {
            if ($key == $name) {
                return $value;
            }
        }
        return null;
    }

    public function ignoreRoute(string $routeName): bool
    {
        // you can assess this controller with out sign in


        $ignoreRestFullRoutes = [
            'home',
            'msc',
            'test'
        ];


        foreach ($ignoreRestFullRoutes as $item) {
            if ($item == $routeName) {
                return true;
            }
        }


        return false;
    }



}