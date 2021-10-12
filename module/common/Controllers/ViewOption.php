<?php


namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\ViewsOptionEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class ViewOption extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $viewsOptionEntity = new ViewsOptionEntity();
        $this->urlQueryParam->dataMap($viewsOptionEntity->getDataMap());

        $viewOptionService = Services::viewOptionService();
        $findAllData = $viewOptionService->index($this->urlQueryParam);

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

        $viewOptionService = Services::viewOptionService();
        $findOneData = $viewOptionService->show($id);

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
            'name' => 'required|max_length[255]',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $viewsOptionEntity = new ViewsOptionEntity((array)$this->request->getVar());
        $viewOptionService = Services::viewOptionService();
        $viewOptionService->create($viewsOptionEntity);


        return $this->respond([

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
            'name' => 'required|max_length[255]',
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),


            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $viewsOptionEntity = new ViewsOptionEntity((array)$this->request->getVar());

        $viewOptionService = Services::viewOptionService();
        $viewOptionService->update($id, $viewsOptionEntity);


        return $this->respond([

        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $viewOptionService = Services::viewOptionService();
        $viewOptionService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }
}
