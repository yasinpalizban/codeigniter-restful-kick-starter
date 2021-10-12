<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\AdvertisementEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class Advertisement extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {
        $advertisementEntity = new AdvertisementEntity();
        $this->urlQueryParam->dataMap($advertisementEntity->getDataMap());

        $advertisementService = Services::advertisementService();
        $findAllData = $advertisementService->index($this->urlQueryParam);


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


        $advertisementService = Services::advertisementService();
        $findOneData = $advertisementService->show($id);

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
            'name' => 'required|min_length[3]|max_length[255]',
            'link' => 'required',


        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };


        $advertisementEntity = new AdvertisementEntity((array)$this->request->getVar());
        $advertisementEntity->enableStatus()->createdAt();
        $advertisementService = Services::advertisementService();
        $advertisementService->create($advertisementEntity);


        return $this->respond([
            'insertId' => $advertisementService->getInsertID()
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
                'name' => 'required|min_length[3]|max_length[255]',
                'link' => 'required',


            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'error' => $this->validator->getErrors(),

                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

            }


            $advertisementEntity = new AdvertisementEntity((array)$this->request->getVar());
            $advertisementEntity->updatedAt();

            $advertisementService = Services::advertisementService();
            $advertisementService->update($id, $advertisementEntity);


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

        $advertisementService = Services::advertisementService();
        $advertisementService->delete($id);

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
