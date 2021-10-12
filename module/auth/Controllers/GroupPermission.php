<?php namespace Modules\Auth\Controllers;


use Modules\Auth\Config\Services;
use Modules\Auth\Entities\GroupsPermissionEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;

class  GroupPermission extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $groupsPermissionEntity = new GroupsPermissionEntity();

        $this->urlQueryParam->dataMap($groupsPermissionEntity->getDataMap());


        $groupsPermissionService = Services::groupsPermissionService();
        $findAllData = $groupsPermissionService->index($this->urlQueryParam);

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
        $groupsPermissionService = Services::groupsPermissionService();
        $findOneData = $groupsPermissionService->show($id);

        return $this->respond([
            'data' => $findOneData['data'],
            'pager' => $findOneData['pager']
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }

    public function create()
    {


        $rules = [
            'groupId' => 'required',
            'permissionId' => 'required',
            'actions' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $groupsPermissionEntity = new GroupsPermissionEntity((array)$this->request->getVar());

        $groupsPermissionService = Services::groupsPermissionService();

        $groupsPermissionService->create($groupsPermissionEntity);


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
            'groupId' => 'required',
            'permissionId' => 'required',
            'actions' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }

        $groupsPermissionEntity = new GroupsPermissionEntity((array)$this->request->getVar());

        $groupsPermissionService = Services::groupsPermissionService();
        $groupsPermissionService->update($id, $groupsPermissionEntity);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $groupsPermissionService = Services::groupsPermissionService();
        $groupsPermissionService->delete($id);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }


}
