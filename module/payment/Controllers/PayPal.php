<?php namespace Modules\Payment\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class  PayPal extends ApiController
{
    /**
     * index function
     * @method : GET
     */

    public function index()
    {

        return $this->respond([
            'data' => '',
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }

    public function create()
    {


        return $this->respond([
            'data' => '',
        ], ResponseInterface::HTTP_OK, lang('Shared.api.save'));

    }


}
