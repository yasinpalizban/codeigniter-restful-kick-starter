<?php namespace Modules\Common\Filters;

use Modules\Shared\Enums\FilterErrorType;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use  CodeIgniter\Config\Services;


class UrlFilter implements FilterInterface
{
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }

    public function before(RequestInterface $request, $arguments = null)
    {


        $response = Services::response();
        $uri = current_url();
        $pathArray = array();
        if (strlen($uri) > 1) {
            $pathArray = explode('/', $uri);
        }


        if (count($pathArray) <= 1) {
            return $response->setJSON(['success' => false,
                'error' => lang('Commmon.filter.url'),
                'type' => FilterErrorType::Url])->setContentType('application/json')
                ->setStatusCode(Response::HTTP_BAD_REQUEST, lang('Commmon.filter.url'));
        }
        if (in_array('api', $pathArray) === false) {
            return $response->setJSON(['success' => false,
                'error' => lang('Commmon.filter.url'),
                'type' => FilterErrorType::Url])->setContentType('application/json')
                ->setStatusCode(Response::HTTP_BAD_REQUEST, lang('Commmon.filter.url'));

        }


    }


}
