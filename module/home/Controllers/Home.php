<?php namespace Modules\Home\Controllers;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;
use Modules\Shared\Libraries\UrlQueryParam;


class   Home extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        return $this->respond([
            'data' => ''
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }


}
