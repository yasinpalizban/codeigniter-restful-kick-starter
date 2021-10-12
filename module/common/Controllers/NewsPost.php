<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\NewsPostEntity;
use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Controllers\ApiController;

class NewsPost extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $newsPostEntity = new NewsPostEntity();
        $this->urlQueryParam->dataMap($newsPostEntity->getDataMap());


        $newsPostService = Services::newsPostService();
        $findAllData = $newsPostService->index($this->urlQueryParam);

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


        $newsPostService = Services::newsPostService();
        $findOneData = $newsPostService->show($id);

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
            'title' => 'required|min_length[3]|max_length[255]',
            'body' => 'required',
            'categoryId' => 'required',
            'subCategoryId' => 'required',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };

        $newsPostEntity = new NewsPostEntity((array)$this->request->getVar());
        $newsPostEntity->userId = $this->userObject->id;
        $newsPostEntity->disableStatus()->createdAt()->initPicture();
        $newsPostService = Services::newsPostService();
        $newsPostService->create($newsPostEntity);


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
            'title' => 'required|min_length[3]|max_length[255]',
            'body' => 'required',
            'categoryId' => 'required',
            'subCategoryId' => 'required',
            'picture' => 'required',
            'status' => 'required',

        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));
        }


        $newsPostEntity = new NewsPostEntity((array)$this->request->getVar());;
        $newsPostEntity->userId= $this->userObject->id;
        $newsPostEntity->updatedAt();
        $newsPostService = Services::newsPostService();
        $newsPostService->update($id, $newsPostEntity);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $newsPostService = Services::newsPostService();
        $newsPostService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

}
