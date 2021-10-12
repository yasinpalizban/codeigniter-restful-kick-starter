<?php namespace Modules\Common\Filters;

use Modules\Shared\Enums\FilterErrorType;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use  CodeIgniter\Config\Services;


class DataInputFilter implements FilterInterface
{
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }

    public function before(RequestInterface $request, $arguments = null)
    {

        $response = Services::response();


        if (!$request->getPost() && !$request->getJSON() && !$request->getFiles()) {
            return $response->setJSON(['success' => false,
                'error' => lang('Commmon.filter.dataInput'),
                'type' => FilterErrorType::DataInput])->setContentType('application/json')
                ->setStatusCode(Response::HTTP_BAD_REQUEST, lang('Commmon.filter.dataInput'));
        }


    }


}
