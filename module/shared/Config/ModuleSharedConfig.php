<?php namespace Modules\Shared\Config;

use CodeIgniter\Config\BaseConfig;

class ModuleSharedConfig extends BaseConfig
{
    /**
     * --------------------------------------------------------------------
     * Default App Site Config
     * --------------------------------------------------------------------
     *
     */


#--------------------------------------------------------------------
# raygansms.com sms services
#--------------------------------------------------------------------
    public $sms = [
        'cellPhoneNumber' => 5000299556794,
        'blackSharePhoneNumber' => 50002910001080,
        'sharePhoneNumber' => 5000248725,
        'userName' => 'paliz3d',
        'password' => 'yasinwxyz1234',
        'smsClass' => 1
    ];

    #--------------------------------------------------------------------
# pusher.com chat real time services
#--------------------------------------------------------------------
    public $pusher = [
        'authKey' => '3bf76050d243c21ee0ce',
        'secret' => '1c4700de2627dfbd5b7d',
        'appId' => '1163012',
        'cluster' => 'ap2',
        'useTLS' => true
    ];


    /*
     *---------------------------------------------------------------
     * public directory
     *---------------------------------------------------------------

     */

    /*
           *---------------------------------------------------------------
           * root directory
           *---------------------------------------------------------------

           */

    public $rootDirectory = __DIR__ . '../../../';

    /*
             *---------------------------------------------------------------
             * upload directory
             *---------------------------------------------------------------

             */

    public $uploadDirectory = __DIR__ . '../../../public/upload';

}
