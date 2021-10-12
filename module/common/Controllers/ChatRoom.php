<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\ChatRoomEntity;


use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Config\ModuleSharedConfig;
use Modules\Shared\Controllers\ApiController;
use Pusher\Pusher;


class ChatRoom extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $chatRoomService = Services::chatRoomService();
        $chatRoomService->setGroupId($this->userObject->groupId);
        $findAllData = $chatRoomService->index($this->urlQueryParam);

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

        $chatRoomService = Services::chatRoomService();
        $findOneData = $chatRoomService->show($id);

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

        $chatRoomService = Services::chatRoomService();


        $rules = [
            'message' => 'required|min_length[1]|max_length[255]',
            'replyId' => 'required',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };

        $chatRoomEntity = new ChatRoomEntity((array)$this->request->getVar());
        $chatRoomEntity->userId = $this->userObject->id;
        $chatRoomEntity->groupId = $this->userObject->groupId;
        $chatRoomEntity->createdAt()->disableStatus();


        $chatRoomService->create($chatRoomEntity);

        $sharedConfig = new ModuleSharedConfig();
        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );

        $data['id'] = $chatRoomService->getInsertID();
        if ($this->request->getVar('replyId') > 0)
            $data['replyMessage'] = $this->request->getVar('message');

        else
            $data['message'] = $this->request->getVar('message');

        $data['replyId'] = $this->request->getVar('replyId');
        $data['groupId'] = $this->userObject->groupId;
        $data['userId'] = $this->userObject->id;
        $data['createdAt'] = $chatRoomEntity->createdAt;
        $data['media'] = [];
        //$data['chatRoomMedia'] = $chatRoomModel->appendMediaRows($id);
        $pusher->trigger('room-channel', 'my-event', $data);


        return $this->respond([
            'insertId' => $chatRoomService->getInsertID(),
        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));


    }

    /**
     * update function
     * @method : PUT or PATCH
     */
    public function update($id = null)
    {
        $chatRoomService = Services::chatRoomService();


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


        $chatRoomEntity = new ChatRoomEntity((array)$this->request->getVar());
        $chatRoomEntity->updatedAt();


        $updated = $chatRoomService->update($id, $chatRoomEntity);

        $sharedConfig = new ModuleSharedConfig();
        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );
        if ($updated->replyId > 0)
            $data['replyMessage'] = $updated->message;
        else
            $data['message'] = $updated->message;


        $data['id'] = $id;
        $data['groupId'] = $this->userObject->groupId;
        $data['userId'] =  $this->userObject->id;
        $data['replyId'] = $updated->replyId;
        $data['createdAt'] = $updated->createdAt;
        $data['media'] = $updated->media;

        $pusher->trigger('room-channel', 'my-event', $data);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $chatRoomService = Services::chatRoomService();
        $chatRoomService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
