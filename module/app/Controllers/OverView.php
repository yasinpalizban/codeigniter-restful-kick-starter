<?php namespace Modules\App\Controllers;


use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Config\Services;
use Modules\Shared\Controllers\ApiController;

class OverView extends ApiController
{

    public function index()
    {
        $newsPostService = Services::newsPostService();
        $visitorService = Services::visitorService();
        $userService = Services::userService();
        $requestPostService = Services::requestReplyService();
        $contactService = Services::contactService();

        return $this->respond([
            'newsPost' => $newsPostService->index($this->urlQueryParam),
            'visitor' => $visitorService->index($this->urlQueryParam),
            'user' => $userService->index($this->urlQueryParam),
            'requestPost' => $requestPostService->index($this->urlQueryParam),
            'contactPost' => $contactService->index($this->urlQueryParam),

        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));

    }


}
