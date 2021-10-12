<?php

namespace Modules\Common\Controllers;

use Modules\Common\Config\ModuleCommonConfig;
use Modules\Common\Config\Services;
use Modules\Common\Entities\ChatRoomMediaEntity;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class ChatRoomMedia extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $chatRoomMediaEntity = new ChatRoomMediaEntity();

        $this->urlQueryParam->dataMap($chatRoomMediaEntity->getDataMap());


        $chatRoomMediaService = Services::chatRoomMediaService();
        $findAllData = $chatRoomMediaService->index($this->urlQueryParam);

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

        $chatRoomMediaService = Services::chatRoomMediaService();
        $findOneData = $chatRoomMediaService->show($id);

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

        $customConfig = new ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();
        $chatRoomMediaEntity = new ChatRoomMediaEntity();
        $chatRoomMediaService = Services::chatRoomMediaService();


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,webp,jpeg,jpg,gif]',
            'chatRoomId' => 'required'

        ];
        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }

        $chatRoomMediaEntity->chatRoomId = $this->request->getVar('chatRoomId');
        if (isset($_FILES['image'])) {


            foreach ($this->request->getFileMultiple('image') as $avatar) {


                $avatar->move($customConfig->chatRoomDirectory, time() . '.' . $avatar->getClientExtension());

                $systemPath = $customConfig->chatRoomDirectory . "/" . $avatar->getName();

                $chatRoomMediaEntity->editPath($avatar->getName());
                if ($avatar->getClientExtension() != 'gif') {
                    $imageService->withFile($systemPath)
                        ->withResource()->resize(200,200)
                        ->save($systemPath, 90);


                }


            }
        }

        $chatRoomMediaService->create($chatRoomMediaEntity);

        return $this->respond([

        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));


    }

    /**
     * update function
     * @method : PUT or PATCH
     */
    public function update($id = null)
    {

        $chatRoomMediaService = Services::chatRoomMediaService();
        $customConfig = new ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();
        $chatRoomMediaEntity = new ChatRoomMediaEntity();
        $data = [];


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,jpg,webp,gif]',
            'chatRoomId' => 'required'
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $chatRoomMediaEntity->chatRoomId = $this->request->getVar('chatRoomId');
        $chatRoomMediaEntity->id = $id;

        if (isset($_FILES['image'])) {


            foreach ($this->request->getFileMultiple('image') as $avatar) {

                $avatar->move($customConfig->chatRoomDirectory, time() . '.' . $avatar->getClientExtension());


                $chatRoomMediaEntity->editPath($avatar->getName());
                $systemPath = $customConfig->chatRoomDirectory . "/" . $avatar->getName();

                if ($avatar->getClientExtension() != 'gif') {
                    $imageService->withFile($systemPath)
                        ->withResource()->resize(200,200)
                        ->save($systemPath, 90);


                }


            }

            $data = ['id' => $id,
                'chat_room_id' => $this->request->getVar('chatRoomId'),
                'path' => $chatRoomMediaEntity->path];
        }

        $chatRoomMediaService->update($id, $chatRoomMediaEntity);

        return $this->respond([
            'data' => $data
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {
        $chatRoomMediaService = Services::chatRoomMediaService();
        $chatRoomMediaService->delete($id, $this->urlQueryParam->getForeignKey());

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }


}
