<?php namespace Modules\Home\Controllers;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class   Test extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        return $this->respond([
            'data' => 'test'

        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));

    }


}
