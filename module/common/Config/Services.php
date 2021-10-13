<?php namespace Modules\Common\Config;


use Config\Services as BaseService;

use Modules\Common\Services\ProfileService;
use Modules\Common\Services\SettingService;
use Modules\Common\Services\UserService;


class Services extends BaseService
{
    
    //--------------------------------------------------------------------

    public static function userService($getShared = false)
    {
        if (!$getShared) {


            return new UserService();
        }

        return static::getSharedInstance('userService');
    }
    //--------------------------------------------------------------------

    public static function settingService($getShared = false)
    {
        if (!$getShared) {


            return new SettingService();
        }

        return static::getSharedInstance('settingService');
    }
   
    public static function profileService($getShared = false)
    {
        if (!$getShared) {


            return new ProfileService();
        }

        return static::getSharedInstance('profileService');
    }

}
