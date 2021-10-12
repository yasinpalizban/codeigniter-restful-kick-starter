<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\NewsCommentEntity;
use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Controllers\ApiController;

class NewsComment extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $newsCommentEntity = new NewsCommentEntity();
        $this->urlQueryParam->dataMap($newsCommentEntity->getDataMap());

        $newsCommentService = Services::newsCommentService();
        $findAllData = $newsCommentService->index($this->urlQueryParam);

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

        $newsCommentService = Services::newsCommentService();
        $findOneData = $newsCommentService->show($id);

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

            'message' => 'required',
            'postId' => 'required',
            'replyId' => 'required',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        };

        $newsCommentEntity = new NewsCommentEntity((array)$this->request->getVar());
        $newsCommentEntity->userId = $this->userObject->id;
        $newsCommentEntity->createdAt();

        $newsCommentService = Services::newsCommentService();

        $newsCommentService->create($newsCommentEntity);


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

                'message' => 'required',


            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'error' => $this->validator->getErrors(),
                    
                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

            }

            $newsCommentEntity = new NewsCommentEntity((array)$this->request->getVar());

            $newsCommentEntity->id = $id;
            $newsCommentEntity->userId = $this->userObject->id;
            $newsCommentEntity->updatedAt();

            $newsCommentService = Services::newsCommentService();

            $newsCommentService->update($id, $newsCommentEntity);


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

        $newsCommentService = Services::newsCommentService();
        $newsCommentService->delete($id, $this->urlQueryParam->getFiled());
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
