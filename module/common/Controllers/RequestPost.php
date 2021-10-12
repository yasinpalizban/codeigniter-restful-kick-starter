<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\RequestPostEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Config\ModuleSharedConfig;
use Modules\Shared\Controllers\ApiController;
use Modules\Shared\Enums\NotificationType;
use Pusher\Pusher;


class RequestPost extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $requestPostEntity = new RequestPostEntity();
        $this->urlQueryParam->dataMap($requestPostEntity->getDataMap());

        $requestPostService = Services::requestPostService();
        $requestPostService->setUserGroupName($this->userObject->groupName);
        $requestPostService->setUserId($this->userObject->id);
        $findAllData = $requestPostService->index($this->urlQueryParam);

        return $this->respond([
            'data' => $findAllData['data'],
            'pager' => $findAllData['pager']
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));

    }

    /**
     * show function
     * @method : GET with params ID
     */
    public function show($id = null)
    {
        $requestPostService = Services::requestPostService();
        $findOneData = $requestPostService->show($id);

        return $this->respond([
            'data' => $findOneData['data'],
            'pager' => $findOneData['pager']
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }

    /**
     * create function
     * @method : POST
     */
    public function create()
    {


        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'body' => 'required',
            'categoryId' => 'required',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };

        $requestPostEntity = new RequestPostEntity((array)$this->request->getVar());
        $requestPostEntity->userId = $this->userObject->id;
        $requestPostEntity->disableStatus()->createdAt();

        $requestPostService = Services::requestPostService();
        $requestPostService->create($requestPostEntity);

        //   Success!
        $sharedConfig = new ModuleSharedConfig();
        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );
        $data['type'] = NotificationType::NewRequest;
        $data['message'] = 'new user Request';
        $data['counter'] = 1;
        $data['date'] = date('Y-m-d H:i:s', time());;
        $pusher->trigger('notification-channel', 'my-event', $data);


        return $this->respond([
            'data' => ''
        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));


    }

    /**
     * update function
     * @method : PUT or PATCH
     */
    public function update($id = null)
    {

        if ($this->request) {
            //get request from Vue Js
            $json = $this->request->getJSON();
            if (!isset($id)) {
                $id = $json->id;
            }


            $rules = [
                'title' => 'required|min_length[3]|max_length[255]',
                'body' => 'required',
                'categoryId' => 'required',
                'status' => 'required',

            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'error' => $this->validator->getErrors(),
                    
                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));
            }


            $requestPostEntity = new RequestPostEntity((array)$this->request->getVar());
            $requestPostEntity->userId = $this->userObject->id;
            $requestPostEntity->updatedAt()->fixStatus();

            $requestPostService = Services::requestPostService();
            $requestPostService->update($id, $requestPostEntity);


        }
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {
        $requestPostService = Services::requestPostService();
        $requestPostService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

}
