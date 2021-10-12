<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Common\Entities\VisitorEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class Visitor extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $visitorEntity = new VisitorEntity();

        $this->urlQueryParam->dataMap($visitorEntity->getDataMap());


        $visitorService = Services::visitorService();
        $findAllData = $visitorService->index($this->urlQueryParam);

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


        $visitorService = Services::visitorService();
        $findOneData = $visitorService->show($id);
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



            helper('ip');
            $userInfo = getCountryByIp($this->request->getIPAddress());
            $userInfo['os'] = $this->request->getUserAgent()->getPlatform() ?? 'n/a';;
            $userInfo['ip'] = $this->request->getIPAddress() ?? 'n/a';
            $visitorEntity = new VisitorEntity($userInfo);
            $visitorEntity->createdAt();
            $visitorService = Services::visitorService();
            $visitorService->create($visitorEntity);



        return $this->respond([

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
            helper('ip');
            $userInfo = getCountryByIp($this->request->getIPAddress());
            $userInfo['id'] = $id;
            $userInfo['os'] = $this->request->getUserAgent()->getPlatform() ?? 'n/a';;
            $userInfo['ip'] = $this->request->getIPAddress() ?? 'n/a';
            $visitorEntity = new VisitorEntity($userInfo);

            $visitorEntity->updatedAt();

            $visitorService = Services::visitorService();
            $visitorService->update($id, $visitorEntity);



        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public
    function delete($id = null)
    {
        $visitorService = Services::visitorService();
        $visitorService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));

    }

}
