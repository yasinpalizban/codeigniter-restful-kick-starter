<?php namespace Modules\Common\Config;

use CodeIgniter\Config\BaseConfig;

class ModuleCommonConfig extends BaseConfig
{

    /*
        *---------------------------------------------------------------
        *  advertisement directory
        *---------------------------------------------------------------

        */
    public $advertiseDirectory = [
        'image' => __DIR__ . '/../../../public/upload/advertisement/image',
        'video' => __DIR__ . '/../../../public/upload/advertisement/video'
    ];

    /*
          *---------------------------------------------------------------
          *  chat private directory
          *---------------------------------------------------------------

          */
    public $chatPrivateDirectory = __DIR__ . '/../../../public/upload/chat_private';
    /*
              *---------------------------------------------------------------
              *  chat room directory
              *---------------------------------------------------------------

              */
    public $chatRoomDirectory = __DIR__ . '/../../../public/upload/chat_room';
    /*
                *---------------------------------------------------------------
                *  contact directory
                *---------------------------------------------------------------

                */
    public $contactDirectory = __DIR__ . '/../../../public/upload/contact';

    /*
                  *---------------------------------------------------------------
                  *  news directory
                  *---------------------------------------------------------------

                  */
    public $newsDirectory = [
        'image' => __DIR__ . '/../../../public/upload/news/image',
        'video' => __DIR__ . '/../../../public/upload/news/video',
        'thumbnail' => __DIR__ . '/../../../public/upload/news/thumbnail/',
    ];
    /*
   *---------------------------------------------------------------
    *  profile directory
    *---------------------------------------------------------------

    */
    public $profileDirectory = __DIR__ . '/../../../public/upload/profile';
    /*
       *---------------------------------------------------------------
        *  view  directory
        *---------------------------------------------------------------

        */
    public $viewDirectory =[
        'image' => __DIR__ . '/../../../public/upload/view/image',
        'video' => __DIR__ . '/../../../public/upload/view/video'
    ];

}
