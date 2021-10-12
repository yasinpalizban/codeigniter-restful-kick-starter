<?php namespace Modules\Home\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use Modules\Shared\Controllers\ApiController;


class   Msc extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        return $this->respond([
            'data' => [''],
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }


}
