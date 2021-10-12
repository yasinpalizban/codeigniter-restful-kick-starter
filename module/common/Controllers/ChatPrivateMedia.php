<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\ChatPrivateMediaEntity;

use Modules\Common\Libraries\CustomFile;


use Modules\Common\Models\ChatPrivateMediaModel;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class ChatPrivateMedia extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $chatPrivateMediaModel = new ChatPrivateMediaModel();

        $chatPrivateMediaEntity = new ChatPrivateMediaEntity();

        $this->urlQueryParam->dataMap($chatPrivateMediaEntity->getDataMap());


        $chatPrivateMediaService = Services::chatPrivateMediaService();
        $findAllData = $chatPrivateMediaService->index($this->urlQueryParam);

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

        $chatPrivateMediaService = Services::chatPrivateMediaService();
        $findOneData = $chatPrivateMediaService->show($id);

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

        $customConfig = new \Modules\Common\Config\ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();

        $chatPrivateMediaEntity = new ChatPrivateMediaEntity();


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,webp,jpeg,jpg,gif]',
            'chatPrivateId' => 'required'
        ];
        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                'success' => false
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }

        $chatPrivateMediaEntity->chatPrivateId = $this->request->getVar('chatPrivateId');
        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {

                $avatar->move($customConfig->chatPrivateDirectory, time() . '.' . $avatar->getClientExtension());


                $chatPrivateMediaEntity->path = $avatar->getName();
                $chatPrivateMediaEntity->editPath();
                if ($avatar->getClientExtension() != 'gif') {
                    $imageService->withFile(ROOTPATH . $chatPrivateMediaEntity->path)
                        ->withResource()->resize(200,200)
                        ->save(ROOTPATH . $chatPrivateMediaEntity->path, 90);

                }



            }
        }
        $chatPrivateMediaService = Services::chatPrivateMediaService();
        $chatPrivateMediaService->create($chatPrivateMediaEntity);

        return $this->respond([

        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));


    }

    /**
     * update function
     * @method : PUT or PATCH
     */
    public function update($id = null)
    {


        $customConfig = new \Modules\Common\Config\ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();
        $chatPrivateMediaEntity = new ChatPrivateMediaEntity();
        $data = [];


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,jpg,webp,gif]',
            'chatPrivateId' => 'required'
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                'success' => false
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $chatPrivateMediaEntity->chatPrivateId = $this->request->getVar('chatPrivateId');
        $chatPrivateMediaEntity->id = $id;
        if (isset($_FILES['image'])) {


            foreach ($this->request->getFileMultiple('image') as $avatar) {

                $avatar->move($customConfig->chatPrivateDirectory, time() . '.' . $avatar->getClientExtension());

                $chatPrivateMediaEntity->path = $avatar->getName();
                $chatPrivateMediaEntity->editPath();
                if ($avatar->getClientExtension() != 'gif') {
                    $imageService->withFile(ROOTPATH . $chatPrivateMediaEntity->path)
                        ->withResource()->resize(200,200)
                        ->save(ROOTPATH . $chatPrivateMediaEntity->path, 90);


                }



            }
            $data = ['id' => $id,
                'chatPrivateId' =>$this->request->getVar('chatPrivateId'),
                'path' => $chatPrivateMediaEntity->path];
        }
        $chatPrivateMediaService = Services::chatPrivateMediaService();
        $chatPrivateMediaService->update($id, $chatPrivateMediaEntity);


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

        $chatPrivateMediaModel = new ChatPrivateMediaModel();
        $handy = new CustomFile();


        $id = ($id == 0 ? 0 : $id);

        if ($id == 0) {

            $isExist = $chatPrivateMediaModel->where(['chat_private_id' => $this->urlQueryParam->getForeignKey()])->
            findAll();
            $target = array('chat_private_id' => $this->urlQueryParam->getForeignKey());
        } else {
            $isExist = $chatPrivateMediaModel->where(['id' => $id])->findAll();
            $target = array('id' => $id);
        }


        if ($isExist) {
            $chatPrivateMediaModel->where($target)->delete();
            foreach ($isExist as $path) {

                $handy->removeSingleFile(ROOTPATH . $path->path);
            }


        }


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
