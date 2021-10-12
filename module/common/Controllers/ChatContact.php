<?php namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class  ChatContact extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $chatContactService = Services::chatContactService();
        $chatContactService->setUserId($this->userObject->id);
        $chatContactService->setUserGroupId($this->userObject->groupId);
        $chatContactService->setUserGroupName($this->userObject->groupName);

        $data = $chatContactService->index($this->urlQueryParam);
        return $this->respond([
            'data' => [
                'users' => $data['users'],
                'groups' => $data['groups'],
            ]

        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }


}
