<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\ChatPrivateEntity;


use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Config\ModuleSharedConfig;
use Modules\Shared\Controllers\ApiController;
use Pusher\Pusher;

class ChatPrivate extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {
        $chatPrivateService = Services::chatPrivateService();
        $chatPrivateService->setUserId($this->userObject->id);
        $findAllData = $chatPrivateService->index($this->urlQueryParam);

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

        $chatPrivateService = Services::chatPrivateService();

        $findOneData = $chatPrivateService->show($id);

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
        $chatPrivateService = Services::chatPrivateService();


        $rules = [
            'message' => 'required|min_length[1]|max_length[255]',
            'userReceiverId' => 'required',
            'replyId' => 'required',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };

        $chatPrivateEntity = new ChatPrivateEntity((array)$this->request->getVar());
        $chatPrivateEntity->userSenderId = $this->userObject->id;
        $chatPrivateEntity->createdAt()->disableStatus();

        $chatPrivateService->create($chatPrivateEntity);

        $sharedConfig = new ModuleSharedConfig();
        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );
        $data['id'] = $chatPrivateService->getInsertID();
        if ($this->request->getVar('replyId') > 0)
            $data['replyMessage'] = $this->request->getVar('message');
        else
            $data['message'] = $this->request->getVar('message');

        $data['replyId'] = $this->request->getVar('replyId');
        $data['userReceiverId'] = $this->request->getVar('userReceiverId');;
        $data['userSenderId'] = "" . $this->userObject->id;
        $data['createdAt'] = $chatPrivateEntity->createdAt;
        $data['media'] = [];
        //$data['chatPrivateMedia'] = $chatPrivateModel->appendMediaRows($id);
        $pusher->trigger('pv-channel', 'my-event', $data);


        return $this->respond([
            'insertId' => $chatPrivateService->getInsertID()
        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));


    }

    /**
     * update function
     * @method : PUT or PATCH
     */
    public function update($id = null)
    {
        $chatPrivateService = Services::chatPrivateService();


        //get request from Vue Js
        $json = $this->request->getJSON();
        if (!isset($id)) {
            $id = $json->id;
        }


        $rules = [
            'message' => 'required|min_length[1]|max_length[255]',

        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $chatPrivateEntity = new ChatPrivateEntity((array)$this->request->getVar());
        $chatPrivateEntity->updatedAt();
        $updated = $chatPrivateService->update($id, $chatPrivateEntity);


        $sharedConfig = new ModuleSharedConfig();
        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );
        if ($updated->replyId > 0)
            $data['replyMessage'] = $this->request->getJSON()->message;
        else
            $data['message'] = $this->request->getJSON()->message;

        $data['id'] = $id;
        $data['replyId'] = $updated->replyId;
        $data['userReceiverId'] = $updated->userReceiverId;
        $data['userSenderId'] = "" . $this->userObject->id;
        $data['createdAt'] = $chatPrivateEntity->createdAt;
        $data['media'] = $updated->media;
        $pusher->trigger('pv-channel', 'my-event', $data);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {


        $chatPrivateService = Services::chatPrivateService();
        $chatPrivateService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}

