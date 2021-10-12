<?php namespace Modules\Common\Config;


use Config\Services as BaseService;
use Modules\Common\Services\AdvertisementMediaService;
use Modules\Common\Services\AdvertisementService;
use Modules\Common\Services\ChatContactService;
use Modules\Common\Services\ChatPrivateMediaService;
use Modules\Common\Services\ChatPrivateService;
use Modules\Common\Services\ChatRoomMediaService;
use Modules\Common\Services\ChatRoomService;
use Modules\Common\Services\ContactMediaService;
use Modules\Common\Services\ContactService;
use Modules\Common\Services\NewsCategoryService;
use Modules\Common\Services\NewsCommentService;
use Modules\Common\Services\NewsMediaService;
use Modules\Common\Services\NewsPostService;
use Modules\Common\Services\NewsSubCategoryService;
use Modules\Common\Services\ProfileService;
use Modules\Common\Services\RequestCategoryService;
use Modules\Common\Services\RequestPostService;
use Modules\Common\Services\RequestReplyService;
use Modules\Common\Services\SettingService;
use Modules\Common\Services\UserService;
use Modules\Common\Services\ViewMediaService;
use Modules\Common\Services\ViewOptionService;
use Modules\Common\Services\VisitorService;

class Services extends BaseService
{
    public static function visitorService($getShared = false)
    {
        if (!$getShared) {
            return new VisitorService();

        }
        return static::getSharedInstance('visitorService');
    }
    //--------------------------------------------------------------------

    public static function viewOptionService($getShared = false)
    {
        if (!$getShared) {


            return new ViewOptionService();
        }

        return static::getSharedInstance('viewOptionService');
    }
    //--------------------------------------------------------------------

    public static function viewMediaService($getShared = false)
    {
        if (!$getShared) {


            return new ViewMediaService();
        }

        return static::getSharedInstance('viewMediaService');
    }
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
    //--------------------------------------------------------------------

    public static function requestCategoryService($getShared = false)
    {
        if (!$getShared) {


            return new RequestCategoryService();
        }

        return static::getSharedInstance('requestCategoryService');
    }

    //--------------------------------------------------------------------
    public static function requestPostService($getShared = false)
    {
        if (!$getShared) {


            return new RequestPostService();
        }

        return static::getSharedInstance('requestPostService');
    }
    //--------------------------------------------------------------------

    public static function requestReplyService($getShared = false)
    {
        if (!$getShared) {


            return new RequestReplyService();
        }

        return static::getSharedInstance('requestReplyService');
    }
    //--------------------------------------------------------------------

    public static function profileService($getShared = false)
    {
        if (!$getShared) {


            return new ProfileService();
        }

        return static::getSharedInstance('profileService');
    }

    //--------------------------------------------------------------------

    public static function newsCategoryService($getShared = false)
    {
        if (!$getShared) {


            return new NewsCategoryService();
        }

        return static::getSharedInstance('newsCategoryService');
    }
    //--------------------------------------------------------------------

    public static function newsSubCategoryService($getShared = false)
    {
        if (!$getShared) {


            return new NewsSubCategoryService();
        }

        return static::getSharedInstance('newsSubCategoryService');
    }
    //--------------------------------------------------------------------


    public static function newsPostService($getShared = false)
    {
        if (!$getShared) {


            return new NewsPostService();
        }

        return static::getSharedInstance('newsPostService');
    }
    //--------------------------------------------------------------------

    public static function newsMediaService($getShared = false)
    {
        if (!$getShared) {


            return new NewsMediaService();
        }

        return static::getSharedInstance('newsMediaService');
    }
    //--------------------------------------------------------------------

    public static function newsCommentService($getShared = false)
    {
        if (!$getShared) {


            return new NewsCommentService();
        }

        return static::getSharedInstance('newsCommentService');
    }

    //--------------------------------------------------------------------



    public static function contactMediaService($getShared = false)
    {
        if (!$getShared) {


            return new ContactMediaService();
        }

        return static::getSharedInstance('contactMediaService');
    }
    //--------------------------------------------------------------------

    public static function contactService($getShared = false)
    {
        if (!$getShared) {


            return new ContactService();
        }

        return static::getSharedInstance('contactService');
    }
    //--------------------------------------------------------------------

    public static function advertisementService($getShared = false)
    {
        if (!$getShared) {


            return new AdvertisementService();
        }

        return static::getSharedInstance('advertisementService');
    }
    //--------------------------------------------------------------------

    public static function advertisementMediaService($getShared = false)
    {
        if (!$getShared) {


            return new AdvertisementMediaService();
        }

        return static::getSharedInstance('advertisementMediaService');
    }
    //--------------------------------------------------------------------

    public static function chatRoomMediaService($getShared = false)
    {
        if (!$getShared) {


            return new ChatRoomMediaService();
        }

        return static::getSharedInstance('chatRoomMediaService');
    }
    //--------------------------------------------------------------------

    public static function chatPrivateMediaService($getShared = false)
    {
        if (!$getShared) {


            return new  ChatPrivateMediaService();
        }

        return static::getSharedInstance('chatPrivateMediaService');
    }
    //--------------------------------------------------------------------

    public static function chatRoomService($getShared = false)
    {
        if (!$getShared) {


            return new ChatRoomService();
        }

        return static::getSharedInstance('chatRoomService');
    }
    //--------------------------------------------------------------------


    public static function chatPrivateService($getShared = false)
    {
        if (!$getShared) {


            return new ChatPrivateService();
        }

        return static::getSharedInstance('chatPrivateService');
    }
    //--------------------------------------------------------------------

    public static function chatContactService($getShared = false)
    {
        if (!$getShared) {


            return new ChatContactService();
        }

        return static::getSharedInstance('chatContactService');
    }
    //--------------------------------------------------------------------

}
