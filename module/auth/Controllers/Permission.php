<?php

namespace Modules\Auth\Controllers;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Auth\Config\Services;
use Modules\Auth\Entities\PermissionEntity;
use Modules\Shared\Controllers\ApiController;


class Permission extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $permissionEntity = new PermissionEntity();

        $this->urlQueryParam->dataMap($permissionEntity->getDataMap());


        $permissionService = Services::permissionService();
        $findAllData = $permissionService->index($this->urlQueryParam);

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
        $permissionService = Services::permissionService();
        $findOneData = $permissionService->show($id);

        return $this->respond([
            'data' => $findOneData['data'],
            'pager' => $findOneData['pager']
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }

    public function create()
    {


        $rules = [
            'name' => 'required|min_length[3]|max_length[255]|is_unique[auth_permissions.name]',
            'description' => 'required|min_length[3]|max_length[255]',
            'active'=>'required'
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $permissionEntity = new  PermissionEntity((array)$this->request->getVar());

        $permissionService = Services::permissionService();
        $permissionService->create($permissionEntity);


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


        $json = $this->request->getJSON();
        if (!isset($id)) {
            $id = $json->id;
        }


        $rules = [
            'name' => 'if_exist|required|min_length[3]|max_length[255]',
            'description' => 'required|min_length[3]|max_length[255]',
            'active'=>'required'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $permissionEntity = new  PermissionEntity((array)$this->request->getVar());

        $permissionService = Services::permissionService();
        $permissionService->update($id, $permissionEntity);


        return $this->respond([
            $permissionEntity
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $permissionService = Services::permissionService();
        $permissionService->delete($id);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
