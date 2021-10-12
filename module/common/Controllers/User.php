<?php namespace Modules\Common\Controllers;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Config\Services;
use Modules\Shared\Controllers\ApiController;

class  User extends ApiController
{

    /**
     * index function
     * @method : GET
     */
    public function index()
    {


        $usersEntity = new   \Myth\Auth\Entities\User();

        $this->urlQueryParam->dataMap($usersEntity->getDataMap());

        $userService = Services::userService();
        $findAllData = $userService->index($this->urlQueryParam);

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
        $userService = Services::userService();
        $findOneData = $userService->show($id);
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



            // Validate here first, since some things,
            // like the password, can only be validated properly here.
            // strong password didint work custom validation  strong_password
            // password=> strong_password
            $rules = [
                'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required',
                'first_name' => 'if_exist|required|max_length[255]',
                'last_name' => 'if_exist|required|max_length[255]',
                'phone' => 'if_exist|required|max_length[11]',
                'group' => 'required|alpha',
            ];

            if (!$this->validate($rules)) {


                return $this->respond([
                    'error' => $this->validator->getErrors(),
                    
                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));


            }

            //  Save the user

            $userEntity = new \Myth\Auth\Entities\User((array)$this->request->getVar());
            $userEntity->activate()->disableStatus();

            $userService = Services::userService();

            $userService->create($userEntity);



        return $this->respond([

        ], ResponseInterface::HTTP_CREATED, lang('Shared.api.save'));

    }

    /**
     * update function
     * @method : PUT or PATCH
     */
    public function update($id = null)
    {
        //model



            $json = $this->request->getJSON();
            if (!isset($id)) {
                $id = $json->id;
            }


            // Validate here first, since some things,
            // like the password, can only be validated properly here.
            // strong password didint work custom validation  strong_password
            // password=> strong_password
            $rules = [

                'first_name' => 'if_exist|required|max_length[255]',
                'last_name' => 'if_exist|required|max_length[255]',
                'phone' => 'if_exist|required|max_length[11]',
                'group' => 'if_exist|required|alpha',
                'status' => 'if_exist|required',
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'error' => $this->validator->getErrors(),
                    
                ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

            }


            $userEntity = new \Myth\Auth\Entities\User((array)$this->request->getVar());

            $userService = Services::userService();

            $userService->update($id, $userEntity);




        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.update'));


    }

    /**
     * edit function
     * @method : DELETE with params ID
     */
    public function delete($id = null)
    {


        $userService = Services::userService();
        $userService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }

}
