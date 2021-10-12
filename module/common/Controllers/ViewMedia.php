<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\ModuleCommonConfig;
use Modules\Common\Config\Services;
use Modules\Common\Entities\ViewsMediaEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class ViewMedia extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $viewsMediaEntity = new ViewsMediaEntity();
        $this->urlQueryParam->dataMap($viewsMediaEntity->getDataMap());


        $viewMediaService = Services::viewMediaService();
        $findAllData = $viewMediaService->index($this->urlQueryParam);

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

        $viewMediaService = Services::viewMediaService();

        $findOneData = $viewMediaService->show($id);

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

        $customConfig = new ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();
        $viewsMediaEntity = new ViewsMediaEntity();

        $rules = [
            'image' => 'if_exist|uploaded[image]|max_size[image,4096]|ext_in[image,png,webp,jpeg,jpg,,mp4,gif,webp]',
            'viewOptionId' => 'required'
        ];
        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }
        $viewsMediaEntity->viewOptionId = $this->request->getVar('viewOptionId');


        if (isset($_FILES['image'])) {


            foreach ($this->request->getFileMultiple('image') as $avatar) {

                if ($avatar->getClientExtension() == 'mp4')
                    $avatar->move($customConfig->viewDirectory['video'], time() . '.' . $avatar->getClientExtension());
                else
                    $avatar->move($customConfig->viewDirectory['image'], time() . '.' . $avatar->getClientExtension());


                $viewsMediaEntity->path = $avatar->getName();
                $viewsMediaEntity->editPath($avatar->getClientExtension() != 'mp4');

                if ($avatar->getClientExtension() != 'mp4' or $avatar->getClientExtension() != 'gif') {
                    $imageService->withFile(ROOTPATH . $viewsMediaEntity->path)
                        ->withResource()
                        ->save(ROOTPATH . $viewsMediaEntity->path, 90);


                }


            }


        }

        $viewMediaService = Services::viewMediaService();
        $viewMediaService->create($viewsMediaEntity);

        return $this->respond([
        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));

    }

    /**
     * update function
     * @method : PUT or PATCH
     */
    public function update($id = null)
    {
        $customConfig = new ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();
        $viewsMediaEntity = new ViewsMediaEntity();


        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,png,webp,jpeg,jpg,,mp4,gif,webp]',
            'viewOptionId' => 'required'
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $viewsMediaEntity->viewOptionId = $this->request->getVar('viewOptionId');
        $viewsMediaEntity->id = $id;

        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {


                if ($avatar->getClientExtension() == 'mp4')
                    $avatar->move($customConfig->viewDirectory['video'], time() . '.' . $avatar->getClientExtension());
                else
                    $avatar->move($customConfig->viewDirectory['image'], time() . '.' . $avatar->getClientExtension());


                $viewsMediaEntity->path = $avatar->getName();
                $viewsMediaEntity->editPath($avatar->getClientExtension() != 'mp4');

                if ($avatar->getClientExtension() != 'mp4' or $avatar->getClientExtension() != 'gif') {

                    $imageService->withFile(ROOTPATH . $viewsMediaEntity->path)
                        ->withResource()
                        ->save(ROOTPATH . $viewsMediaEntity->path, 90);

                }


            }


        }

        $viewMediaService = Services::viewMediaService();
        $viewMediaService->update($id, $viewsMediaEntity);

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {


        $viewMediaService = Services::viewMediaService();
        $viewMediaService->delete($id, $this->urlQueryParam->getForeignKey());

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
