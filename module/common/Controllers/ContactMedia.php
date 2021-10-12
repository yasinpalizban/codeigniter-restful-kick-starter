<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\ModuleCommonConfig;
use Modules\Common\Config\Services;
use Modules\Common\Entities\ContactMediaEntity;

use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Controllers\ApiController;

class ContactMedia extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $contactMediaEntity = new ContactMediaEntity();
        $this->urlQueryParam->dataMap($contactMediaEntity->getDataMap());

        $contactMediaService = Services::contactMediaService();
        $findAllData = $contactMediaService->index($this->urlQueryParam);

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

        $contactMediaService = Services::contactMediaService();
        $findOneData = $contactMediaService->show($id);

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
        $contactMediaEntity = new ContactMediaEntity();


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,jpg,gif,webp]',
            'contactId' => 'required'
        ];
        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }

        $contactMediaEntity->contactId = $this->request->getVar('contactId');
        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {

                $avatar->move($customConfig->contactDirectory, time() . '.' . $avatar->getClientExtension());


                $contactMediaEntity->path = $avatar->getName();
                $contactMediaEntity->editPath();
                $imageService->withFile(ROOTPATH . $contactMediaEntity->path)
                    ->withResource()
                    ->save(ROOTPATH . $contactMediaEntity->path, 90);


            }

        }
        $contactMediaService = Services::contactMediaService();
        $contactMediaService->create($contactMediaEntity);

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


        $customConfig = new ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();

        $data = [];
        $contactMediaEntity = new ContactMediaEntity();


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,jpg,gif,webp]',
            'contactId' => 'required'
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),



            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $contactMediaEntity->contactId = $this->request->getVar('contactId');
        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {
                $avatar->move($customConfig->contactDirectory, time() . '.' . $avatar->getClientExtension());


                $contactMediaEntity->id = $id;
                $contactMediaEntity->path = $avatar->getName();
                $contactMediaEntity->editPath();
                $imageService->withFile(ROOTPATH . $contactMediaEntity->path)
                    ->withResource()
                    ->save(ROOTPATH . $contactMediaEntity->path, 90);


            }
            $data = ['id' => $id,
                'contactId' => $this->request->getVar('contactId'),
                'path' => $contactMediaEntity->path];

        }

        $contactMediaService = Services::contactMediaService();

        $contactMediaService->update($id, $contactMediaEntity);

        return $this->respond([
                'data' => $data]
            , ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {


        $contactMediaService = Services::contactMediaService();
        $contactMediaService->delete($id, $this->urlQueryParam->getForeignKey());

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));

    }

}
