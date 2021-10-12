<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\AdvertisementMediaEntity;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class AdvertisementMedia extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $advertisementMediaEntity = new AdvertisementMediaEntity();

        $this->urlQueryParam->dataMap($advertisementMediaEntity->getDataMap());
        $advertisementMediaService = Services::advertisementMediaService();;
        $findAllData = $advertisementMediaService->index($this->urlQueryParam);

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

        $advertisementMediaService = Services::advertisementMediaService();;

        $findOneData = $advertisementMediaService->show($id);

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
        $advertisementMediaEntity = new AdvertisementMediaEntity();
        $advertisementMediaService = Services::advertisementMediaService();


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,jpg,mp4,gif,webp]',
            'advertisementId' => 'required'
        ];
        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }

        $advertisementMediaEntity->advertisementId = $this->request->getVar('advertisementId');

        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {


                if ($avatar->getClientExtension() == 'mp4')
                    $avatar->move($customConfig->advertiseDirectory['video'], time() . '.' . $avatar->getClientExtension());
                else
                    $avatar->move($customConfig->advertiseDirectory['image'], time() . '.' . $avatar->getClientExtension());


                $advertisementMediaEntity->path = $avatar->getName();
                $advertisementMediaEntity->editPath($avatar->getClientExtension() != 'mp4');


            }


        }

        $advertisementMediaService->create($advertisementMediaEntity);
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
        $advertisementMediaService = Services::advertisementMediaService();
        $advertisementMediaEntity = new AdvertisementMediaEntity();

        $data = [];


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,webp,jpeg,jpg,mp4,gif]',
            'advertisementId' => 'required'
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $advertisementMediaEntity->id = $id;
        $advertisementMediaEntity->advertisementId = $this->request->getVar('advertisementId');

        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {

                if ($avatar->getClientExtension() == 'mp4')
                    $avatar->move($customConfig->advertiseDirectory['video'], time() . '.' . $avatar->getClientExtension());
                else
                    $avatar->move($customConfig->advertiseDirectory['image'], time() . '.' . $avatar->getClientExtension());

                $advertisementMediaEntity->path = $avatar->getName();

                $advertisementMediaEntity->editPath($avatar->getClientExtension() != 'mp4');



            }
            $data = ['id' => $id,
                'advertisementId' => $this->request->getVar('advertisementId'),
                'path' => $advertisementMediaEntity->path];


        }

        $advertisementMediaService->update($id, $advertisementMediaEntity);

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


        $advertisementMediaService = Services::advertisementMediaService();
        $advertisementMediaService->delete($id, $this->urlQueryParam->getForeignKey());

        return $this->respond([

        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));

    }

}
