<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\NewsSubCategoryEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class NewsSubCategory extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $newsSubCategoryEntity = new NewsSubCategoryEntity();

        $this->urlQueryParam->dataMap($newsSubCategoryEntity->getDataMap());

        $newsSubCategoryService = Services::newsSubCategoryService();
        $findAllData = $newsSubCategoryService->index($this->urlQueryParam);

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

        $newsSubCategoryService = Services::newsSubCategoryService();

        $findOneData = $newsSubCategoryService->show($id);

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
            'categoryId' => 'required'

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'data' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };


        $newsSubCategoryEntity = new NewsSubCategoryEntity((array)$this->request->getVar());
        $newsSubCategoryService = Services::newsSubCategoryService();
        $newsSubCategoryService->create($newsSubCategoryEntity);


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
        if ($this->request) {
            //get request from Vue Js
            $json = $this->request->getJSON();
            if (!isset($id)) {
                $id = $json->id;
            }


            $rules = [
                'name' => 'required|min_length[3]|max_length[255]',
                'categoryId' => 'required',

            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'error' => $this->validator->getErrors(),
                    
                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));
            }


            $newsSubCategoryEntity = new NewsSubCategoryEntity((array)$this->request->getVar());
            $newsSubCategoryService = Services::newsSubCategoryService();
            $newsSubCategoryService->update($id, $newsSubCategoryEntity);


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

        $newsSubCategoryService = Services::newsSubCategoryService();
        $newsSubCategoryService->delete($id);

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
