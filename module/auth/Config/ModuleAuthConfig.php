<?php namespace Modules\Auth\Config;

use CodeIgniter\Config\BaseConfig;

class ModuleAuthConfig extends BaseConfig
{
    /**
     * --------------------------------------------------------------------
     * Default Core Auth Config
     * --------------------------------------------------------------------
     *
     */


    /**
     * --------------------------------------------------------------------------
     * Jwt Secret key
     * --------------------------------------------------------------------------
     *
     *
     * @var string
     */
    public $jwt = [
        'secretKey' => 'sljjljtgidhvxvxzfdfarwfsdkk_ayuikjukliebmvlhqewhw',
        'name' => 'Authorization',
    ];


    /**
     * --------------------------------------------------------------------------
     *  log in via gmail
     * --------------------------------------------------------------------------
     *
     *
     * @var string
     */
    public $gmail = [
        'clientId' => '',
        'clientSecret' => '',
    ];

    /**
     * --------------------------------------------------------------------------
     *  log in via gmail
     * --------------------------------------------------------------------------
     *
     *
     * @var string
     */
    public $facebook = [
        'appId' => '',
        'appSecret' => '',
    ];

    #--------------------------------------------------------------------
# google  captcha services
# account yasinpalizban10@gmail.com
# site key ui
# secert key serve
#--------------------------------------------------------------------
    public $captcha = [
        'siteKey' => '6LezT9UaAAAAAM43V6n7F5st_GEadrGTUrmx0232',
        'secretKey' => '6LezT9UaAAAAAPWsjvCeVZqBGVsdpty_s8bYfh_s'
    ];
    /*
        *---------------------------------------------------------------
        *  default user image profile
        *---------------------------------------------------------------

    */
    public $defualtUserProfile = 'public/upload/profile/default-avatar.jpg';
    /*
           *---------------------------------------------------------------
           *  View Form email Activation
           *---------------------------------------------------------------

       */
    public $views = [
        'emailForgot' => 'Modules\Auth\Views\forgot',
        'emailActivation' => 'Modules\Auth\Views\activation',
    ];
}
