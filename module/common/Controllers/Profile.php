<?php namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Controllers\ApiController;


class Profile extends ApiController
{

    /**
     * index function
     * @method : GET
     * @throws \Exception
     */
    public function index()
    {

        $profileService = Services::profileService();
        $profileService->setUserId( $this->userObject->id);
        $data = $profileService->index($this->urlQueryParam);
        return $this->respond([
            'data' => $data['data']
        ], ResponseInterface::HTTP_OK, lang('Shared.api.receive'));
    }


    public function create()
    {


        $customConfig = new \Modules\Common\Config\ModuleCommonConfig();
        $imageService = \CodeIgniter\Config\Services::image();


        $rules = [
            'firstName' => 'if_exist|required|max_length[255]',
            'lastName' => 'if_exist|required|max_length[255]',
            'address' => 'if_exist|required|max_length[255]',
            'phone' => 'if_exist|required|max_length[11]',
            'password' => 'if_exist|required',
            'passConfirm' => 'if_exist|required|matches[password]',
            'gender' => 'if_exist|required',
            'country' => 'if_exist|required|max_length[255]',
            'city' => 'if_exist|required|max_length[255]',
            'image' => 'if_exist|uploaded[image]|max_size[image,4096]|ext_in[avatar,png,jpg,jpeg,webp]',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }

        $userEntity = new \Myth\Auth\Entities\User((array)$this->request->getVar());

        if (isset($_FILES['image'])) {


            $avatar = $this->request->getFile('image');
            $avatar->move($customConfig->profileDirectory, time() . '.' . $avatar->getClientExtension());
            $userEntity->image = $avatar->getName();
            $userEntity->editImage();
            $imageService->withFile(ROOTPATH . $userEntity->image)
                ->withResource()->fit(100, 100, 'center')
                ->save(ROOTPATH . $userEntity->image, 90);
        }

        $profileService = Services::profileService();
        $userEntity->username;
        $profileService->update( $this->userObject->id, $userEntity);


        return $this->respond([
            'success' => true,

        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));

    }


}
