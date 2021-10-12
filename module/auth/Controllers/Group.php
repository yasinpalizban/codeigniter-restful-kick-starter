<?php namespace Modules\Auth\Controllers;


use Modules\Auth\Config\Services;
use Modules\Auth\Entities\GroupEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class  Group extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $groupEntity = new GroupEntity();

        $this->urlQueryParam->dataMap($groupEntity->getDataMap());


        $groupService = Services::groupService();
        $findAllData = $groupService->index($this->urlQueryParam);

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
        $groupService = Services::groupService();
        $findOneData = $groupService->show($id);

        return $this->respond([
            'data' => $findOneData['data'],
            'pager' => $findOneData['pager']
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }

    public function create()
    {


        $rules = [
            'name' => 'required|min_length[3]|max_length[255]|is_unique[auth_groups.name]',
            'description' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $groupEntity = new GroupEntity((array)$this->request->getVar());

        $groupService = Services::groupService();
        $groupService->create($groupEntity);


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

        //get request from Vue Js
        $json = $this->request->getJSON();
        if (!isset($id)) {
            $id = $json->id;
        }


        $rules = [
            'name' => 'if_exist|required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $groupEntity = new GroupEntity((array)$this->request->getVar());

        $groupService = Services::groupService();
        $groupService->update($id, $groupEntity);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $groupService = Services::groupService();
        $groupService->delete($id);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }


}
