<?php namespace Modules\Payment\Config;

use CodeIgniter\Config\BaseConfig;

class ModulePaymentConfig extends BaseConfig
{


    /**
     * --------------------------------------------------------------------
     * Default Core Payment Config
     * --------------------------------------------------------------------
     *
     */
    public $payPal = [
        'sandboxAccount'=>'sb-x47jkb6842963@business.example.com',
        'client_id' => 'Ac6drer-U4PG2JEx4QmUfVgeftGsjeI_iMdMwFfDi7GHzzhgqwP700b5SNwCbbNujfDggN_jnt0TKgEa',
        'secret' => 'ELeAl0_gfb1aKAQeYmgnxayAlNYUmD2mjlWAtClMx-YRGoNeauJH4m94wdxH_6em7VLeYWi8jnpz9x9v',
        'back_url' => '',
        'settings' => [
            'mode' => 'sandbox',
            'http.ConnectionTimeOut' => 1000,
            // 'log.LogEnabled' => true,
            // 'log.LogLevel' => 'FINE',
            // 'log.FileName' => storage_path() . '/logs/paypal.log',

        ]
    ];

    public $zarinPal = [
        'merchant_id' => 'd8802288-00bb-4739-acfd-265d3b7ea9be',
        'back_url' => '',
        ];

}
