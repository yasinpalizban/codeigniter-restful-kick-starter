<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\NewsCategoryEntity;
use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Controllers\ApiController;

class NewsCategory extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $newsCategoryEntity = new NewsCategoryEntity();
        $this->urlQueryParam->dataMap($newsCategoryEntity->getDataMap());


        $newsCategoryService = Services::newsCategoryService();
        $findAllData = $newsCategoryService->index($this->urlQueryParam);

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

        $newsCategoryService = Services::newsCategoryService();
        $findOneData = $newsCategoryService->show($id);
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


        $newsCategoryEntity = new NewsCategoryEntity((array)$this->request->getVar());
        $newsCategoryService = Services::newsCategoryService();
        $newsCategoryService->create($newsCategoryEntity);


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
            'name' => 'required|min_length[3]|max_length[255]',
            'language' => 'required|min_length[2]|max_length[255]',

        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $newsCategoryEntity = new NewsCategoryEntity((array)$this->request->getVar());
        $newsCategoryService = Services::newsCategoryService();
        $newsCategoryService->update($id, $newsCategoryEntity);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {


        $newsCategoryService = Services::newsCategoryService();
        $newsCategoryService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
