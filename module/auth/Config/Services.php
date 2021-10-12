<?php namespace Modules\Auth\Config;


use CodeIgniter\HTTP\UserAgent;
use Config\App;
use Config\Services as AppServices;
use Config\Services as BaseService;
use Modules\Auth\Libraries\RequestWithUser;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Services\GroupsPermissionService;
use Modules\Auth\Services\PermissionService;
use Modules\Auth\Services\RoleRouteService;
use Modules\Auth\Services\GroupService;
use Modules\Auth\Services\UsersPermissionService;

class Services extends BaseService
{
    //--------------------------------------------------------------------

    /**
     * The Request class models an HTTP request.
     *
     * @param App|null $config
     * @param boolean $getShared
     *
     * @return RequestWithUser
     */
    public static function requestWithUser(App $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('requestWithUser', $config);
        }

        $config = $config ?? config('App');;
        return new RequestWithUser(
            $config,
            AppServices::uri(),
            'php://input',
            new UserAgent()
        );
    }

    //--------------------------------------------------------------------


    public static function roleRoute($getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('roleRoute');
        }

        return new RoleRouteService();
    }
//--------------------------------------------------------------------

    public static function authService($getShared = false)
    {
        if (!$getShared) {
            return new AuthService();
        }
        return static::getSharedInstance('authService');

    }
//--------------------------------------------------------------------

    public static function groupService($getShared = false)
    {
        if (!$getShared) {


            return new GroupService();
        }

        return static::getSharedInstance('groupService');
    }
//--------------------------------------------------------------------

    public static function permissionService($getShared = false)
    {
        if (!$getShared) {


            return new PermissionService();
        }

        return static::getSharedInstance('permissionService');
    }
//--------------------------------------------------------------------

    public static function groupsPermissionService($getShared = false)
    {
        if (!$getShared) {


            return new GroupsPermissionService();
        }

        return static::getSharedInstance('groupsPermissionService');
    }
//--------------------------------------------------------------------

    public static function userPermissionService($getShared = false)
    {
        if (!$getShared) {


            return new UsersPermissionService();
        }

        return static::getSharedInstance('usersPermissionService');
    }

//--------------------------------------------------------------------

}
