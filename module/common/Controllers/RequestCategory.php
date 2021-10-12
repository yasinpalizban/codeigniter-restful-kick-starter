<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\RequestCategoryEntity;

use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Controllers\ApiController;


class RequestCategory extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {
        $requestCategoryEntity = new RequestCategoryEntity();

        $this->urlQueryParam->dataMap($requestCategoryEntity->getDataMap());

        $requestCategoryService = Services::requestCategoryService();
        $findAllData = $requestCategoryService->index($this->urlQueryParam);

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
        $requestCategoryService = Services::requestCategoryService();

        $findOneData = $requestCategoryService->show($id);

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
            'language' => 'required|min_length[2]|max_length[255]',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };

        $requestCategoryEntity = new RequestCategoryEntity((array)$this->request->getVar());
        $requestCategoryService = Services::requestCategoryService();
        $requestCategoryService->create($requestCategoryEntity);


        return $this->respond([

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
                'language' => 'required|min_length[2]|max_length[255]',

            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'error' => $this->validator->getErrors(),
                    
                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

            }


            $requestCategoryEntity = new RequestCategoryEntity((array)$this->request->getVar());
            $requestCategoryService = Services::requestCategoryService();
            $requestCategoryService->update($id, $requestCategoryEntity);


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
        $requestCategoryService = Services::requestCategoryService();
        $requestCategoryService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
