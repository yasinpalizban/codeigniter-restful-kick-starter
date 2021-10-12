<?php

namespace Modules\Common\Controllers;


use Modules\Common\Config\ModuleCommonConfig;
use Modules\Common\Config\Services;
use Modules\Common\Entities\NewsMediaEntity;

use CodeIgniter\HTTP\ResponseInterface;

use Modules\Shared\Controllers\ApiController;

class NewsMedia extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $newsMediaEntity = new NewsMediaEntity();
        $this->urlQueryParam->dataMap($newsMediaEntity->getDataMap());

        $newsMediaService = Services::newsMediaService();
        $findAllData = $newsMediaService->index($this->urlQueryParam);

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

        $newsMediaService = Services::newsMediaService();
        $findOneData = $newsMediaService->show($id);

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
        $newsMediaEntity = new NewsMediaEntity();

        $rules = [
            'image' => 'uploaded[image]|max_size[image,4096]|ext_in[image,webp,png,jpeg,jpg,mp4,gif]',
            'postId' => 'required',
        ];
        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }
        $newsMediaEntity->postId = $this->request->getVar('postId');


        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {

                if ($avatar->getClientExtension() == 'mp4') {


                    $avatar->move($customConfig->newsDirectory['video'], time() . '.' . $avatar->getClientExtension());
                } else {
                    $avatar->move($customConfig->newsDirectory['image'], time() . '.' . $avatar->getClientExtension());
                }


                if ($avatar->getClientExtension() == 'mp4') {
                    $newsMediaEntity->video = $avatar->getName();
                    $newsMediaEntity->image = '';
                    $newsMediaEntity->thumbnail = '';
                    $newsMediaEntity->editPathVideo();

                } else if ($avatar->getClientExtension() == 'gif') {
                    $newsMediaEntity->video = '';
                    $newsMediaEntity->image = $avatar->getName();
                    $newsMediaEntity->thumbnail = '';
                    $newsMediaEntity->editPathImage();

                } else {
                    $newsMediaEntity->image = $avatar->getName();
                    $newsMediaEntity->editPathImage();
                    $imageService->withFile(ROOTPATH . $newsMediaEntity->image)->
                    fit(200, 200, 'center')->save($customConfig->newsDirectory['thumbnail'] . $avatar->getName());

                    $imageService->withFile(ROOTPATH . $newsMediaEntity->image)
                        ->withResource()
                        ->save(ROOTPATH . $newsMediaEntity->image, 90);


                    $newsMediaEntity->thumbnail = $avatar->getName();
                    $newsMediaEntity->editPathThumbNail();
                    $newsMediaEntity->video = '';

                }


            }

        }


        $newsMediaService = Services::newsMediaService();
        $newsMediaService->create($newsMediaEntity);

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
        $newsMediaEntity = new NewsMediaEntity();


        $rules = [
            'postId' => 'if_exist|required',
            'image' => 'if_exist|uploaded[image]|max_size[image,4096]|ext_in[image,webp,png,jpeg,jpg,mp4,gif]'
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $newsMediaEntity->postId = $this->request->getVar('postId');
        $newsMediaEntity->id = $id;

        if (isset($_FILES['image'])) {

            foreach ($this->request->getFileMultiple('image') as $avatar) {

                if ($avatar->getClientExtension() == 'mp4') {


                    $avatar->move($customConfig->newsDirectory['video'], time() . '.' . $avatar->getClientExtension());
                } else {
                    $avatar->move($customConfig->newsDirectory['image'], time() . '.' . $avatar->getClientExtension());
                }

                if ($avatar->getClientExtension() == 'mp4') {
                    $newsMediaEntity->video = $avatar->getName();
                    $newsMediaEntity->image = '';
                    $newsMediaEntity->thumbnail = '';
                    $newsMediaEntity->editPathVideo();

                } else if ($avatar->getClientExtension() == 'gif') {
                    $newsMediaEntity->video = '';
                    $newsMediaEntity->image = $avatar->getName();
                    $newsMediaEntity->thumbnail = '';
                    $newsMediaEntity->editPathImage();

                } else {
                    $newsMediaEntity->image = $avatar->getName();
                    $newsMediaEntity->editPathImage();
                    $imageService->withFile(ROOTPATH . $newsMediaEntity->image)->
                    fit(200, 200, 'center')
                        ->save($customConfig->newsDirectory['thumbnail'] . $avatar->getName());

                    $imageService->withFile(ROOTPATH . $newsMediaEntity->image)
                        ->withResource()
                        ->save(ROOTPATH . $newsMediaEntity->image, 90);


                    $newsMediaEntity->thumbnail = $avatar->getName();
                    $newsMediaEntity->editPathThumbNail();
                    $newsMediaEntity->video = '';


                }


            }


        }


        $newsMediaService = Services::newsMediaService();
        $newsMediaService->update($id, $newsMediaEntity);


        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));

    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {

        $newsMediaService = Services::newsMediaService();
        $newsMediaService->delete($id, $this->urlQueryParam->getForeignKey());

        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));

    }

}
