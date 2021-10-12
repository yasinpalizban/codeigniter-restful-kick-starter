<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\SettingEntity;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class Setting extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $settingEntity = new SettingEntity();
        $this->urlQueryParam->dataMap($settingEntity->getDataMap());

        $settingService = Services::settingService();
        $findAllData = $settingService->index($this->urlQueryParam);

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

        $settingService = Services::settingService();


        $findOneData = $settingService->show($id);

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
            'key' => 'required|min_length[3]|max_length[255]|is_unique[setting.key]',
            'value' => 'required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[3]|max_length[255]',
            'status' => 'required',
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $settingEntity = new SettingEntity((array)$this->request->getVar());
        $settingEntity->createdAt();

        $settingService = Services::settingService();
        $settingService->create($settingEntity);


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


        //get request from Vue Js
        $json = $this->request->getJSON();
        if (!isset($id)) {
            $id = $json->id;
        }


        $rules = [
            'key' => 'if_exist|required|min_length[3]|max_length[255]',
            'value' => 'required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[3]|max_length[255]',
            'status' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $settingEntity = new SettingEntity((array)$this->request->getVar());
        $settingEntity->updatedAt();

        $settingService = Services::settingService();
        $settingEntity->updatedAt();

        $settingService->update($id, $settingEntity);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {
        $settingService = Services::settingService();
        $settingService->delete($id);

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));

    }

}
