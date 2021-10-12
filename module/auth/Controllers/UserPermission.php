<?php namespace Modules\Auth\Controllers;


use Modules\Auth\Config\Services;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Auth\Entities\UsersPermissionEntity;
use Modules\Shared\Controllers\ApiController;

class  UserPermission extends ApiController
{
    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $usersPermissionEntity = new UsersPermissionEntity();

        $this->urlQueryParam->dataMap($usersPermissionEntity->getDataMap());


        $userPermissionService = Services::userPermissionService();
        $findAllData = $userPermissionService->index($this->urlQueryParam);

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
        $userPermissionService = Services::userPermissionService();
        $findOneData = $userPermissionService->show($id);

        return $this->respond([
            'data' => $findOneData['data'],
            'pager' => $findOneData['pager']
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));


    }

    public function create()
    {


        $rules = [
            'userId' => 'required',
            'permissionId' => 'required',
            'actions' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $usersPermissionEntity = new UsersPermissionEntity((array)$this->request->getVar());

        $usersPermissionService = Services::userPermissionService();
        $usersPermissionService->create($usersPermissionEntity);


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

            //get request from Vue Js
            $json = $this->request->getJSON();
            if (!isset($id)) {
                $id = $json->id;
            }


            $rules = [
                'userId' => 'required',
                'permissionId' => 'required',
                'actions' => 'required|min_length[3]|max_length[255]',
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'error' => $this->validator->getErrors(),
                    
                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

            }

            $usersPermissionEntity = new UsersPermissionEntity((array)$this->request->getVar());

            $usersPermissionService = Services::userPermissionService();
            $usersPermissionService->update($id, $usersPermissionEntity);

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

        $usersPermissionService = Services::userPermissionService();
        $usersPermissionService->delete($id);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }


}
