<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\RequestReplyEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class RequestReply extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {
        $requestReplyEntity = new RequestReplyEntity();
        $this->urlQueryParam->dataMap($requestReplyEntity->getDataMap());


        $requestReplyService = Services::requestReplyService();
        $findAllData = $requestReplyService->index($this->urlQueryParam);

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
        $requestReplyService = Services::requestReplyService();
        $findOneData = $requestReplyService->show($id);

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


        $requestReplyEntity = new RequestReplyEntity((array)$this->request->getVar());
        $requestReplyEntity->userId = $this->userObject->id;
        $requestReplyEntity->createdAt();

        $requestReplyService = Services::requestReplyService();
        $requestReplyService->create($requestReplyEntity);


        return $this->respond([
            'insertId' => $requestReplyService->getInsertId(),
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

            'message' => 'required',


        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $requestReplyEntity = new RequestReplyEntity((array)$this->request->getVar());
        $requestReplyEntity->updatedAt();

        $requestReplyService = Services::requestReplyService();
        $requestReplyService->update($id, $requestReplyEntity);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $requestReplyService = Services::requestReplyService();
        $requestReplyService->delete($id, $this->urlQueryParam->getForeignKey());

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
