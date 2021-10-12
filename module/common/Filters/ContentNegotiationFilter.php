<?php namespace Modules\Common\Filters;

use CodeIgniter\Config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Modules\Common\Enums\LanguageType;
use Modules\Shared\Enums\FilterErrorType;


class ContentNegotiationFilter implements FilterInterface
{
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {

    }

    public function before(RequestInterface $request, $arguments = null)
    {
        $response = Services::response();

        $negotiate = \CodeIgniter\Config\Services::negotiator();
        //   $negotiate->media(['application/json'], true);
        $lang = $negotiate->language([LanguageType::En, LanguageType::Fa]);
        $encode = $negotiate->encoding(['gzip']);
        $char = $negotiate->charset(['utf-8']);


//        if (!$lang) {
//            return $response->setJSON([
//                'error' => lang('Commmon.filter.contentLang'),
//                'type' => FilterErrorType::Content])->setContentType('application/json')
//                ->setStatusCode(Response::HTTP_BAD_REQUEST, lang('Commmon.filter.contentLang'));
//
//        }

//        if (!$encode) {
//            return $response->setJSON([
//                'error' => lang('Commmon.filter.contentEncode'),
//                'type' => FilterErrorType::Content])->setContentType('application/json')
//                ->setStatusCode(Response::HTTP_BAD_REQUEST, lang('Commmon.filter.contentEncode'));
//
//        }

//        if (!$char) {
//            return $response->setJSON([
//                'error' => lang('Commmon.filter.contentChar'),
//                'type' => FilterErrorType::Content])->setContentType('application/json')
//                ->setStatusCode(Response::HTTP_BAD_REQUEST, lang('Commmon.filter.contentChar'));
//
//        }

    }


}
