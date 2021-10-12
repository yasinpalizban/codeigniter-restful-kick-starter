<?php


namespace Modules\Common\Controllers;


use Modules\Common\Config\Services;
use Modules\Shared\Config\ModuleSharedConfig;
use Modules\Shared\Controllers\ApiController;
use Modules\Shared\Enums\NotificationType;

use Modules\Common\Models\ContactModal;
use Modules\Common\Entities\ContactEntity;

use CodeIgniter\HTTP\ResponseInterface;

use Pusher\Pusher;

class Contact extends ApiController
{


    /**
     * index function
     * @method : GET
     */
    public function index()
    {

        $contactEntity = new ContactEntity();
        $this->urlQueryParam->dataMap($contactEntity->getDataMap());

        $contactService = Services::contactService();
        $findAllData = $contactService->index($this->urlQueryParam);

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

        $contactService = Services::contactService();

        $findOneData = $contactService->show($id);

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
            'title' => 'required|max_length[255]',
            'email' => 'required|valid_email',
            'message' => 'required',
            'name' => 'required',
            'phone' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),

            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }

        $contactEntity = new ContactEntity((array)$this->request->getVar());

        $contactEntity->createdAt()->disableStatus();

        $contactService = Services::contactService();
        $contactService->create($contactEntity);

        $sharedConfig = new ModuleSharedConfig();
        $pusher = new Pusher(
            $sharedConfig->pusher['authKey'],
            $sharedConfig->pusher['secret'],
            $sharedConfig->pusher['appId'],
            ['cluster' => $sharedConfig->pusher['cluster'],
                'useTLS' => $sharedConfig->pusher['useTLS']]
        );
        $data['type'] = NotificationType::NewContact;
        $data['message'] = ' you got new  contact';
        $data['counter'] = 1;
        $data['date'] = date('Y-m-d H:i:s', time());;
        $pusher->trigger('notification-channel', 'my-event', $data);

        return $this->respond([
            'insertId' => $contactService->getInsertID()
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

            'reply' => 'required',

        ];

        if (!$this->validate($rules)) {

            return $this->respond([
                'error' => $this->validator->getErrors(),
                
            ], ResponseInterface::HTTP_NOT_ACCEPTABLE, lang('Shared.api.validation'));

        }


        $contactEntity = new ContactEntity((array)$this->request->getVar());
        $contactEntity->enableStatus()->updatedAt();
        $contactService = Services::contactService();

        $updated = $contactService->update($id, $contactEntity);

        $email = \Codeigniter\Config\Services::email();
        $emailConfig = new \Config\Email();
        $email->initialize($emailConfig);
        $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
        $email->setTo($updated->email);
        $email->setSubject(lang('Commmon.apiEvent.emailReply') . ' ' . $updated->title);
        $email->setMessage($updated->reply);
        $result = $email->send();

        //       print_r(  $email->printDebugger());

        if (!$result) {

            return $this->respond([
                'error' => $email->printDebugger(['headers'])
            ], ResponseInterface::HTTP_BAD_REQUEST, lang('Commmon.apiEvent.emailFail'));

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


        $contactService = Services::contactService();
        $contactService->delete($id);
        return $this->respond([
        ], ResponseInterface::HTTP_OK, lang('Shared.api.remove'));


    }
}
