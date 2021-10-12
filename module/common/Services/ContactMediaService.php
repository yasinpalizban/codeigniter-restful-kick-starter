<?php

namespace Modules\Common\Services;



use Modules\Common\Entities\ContactMediaEntity;
use Modules\Common\Libraries\CustomFile;
use Modules\Common\Models\ContactMediaModal;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class ContactMediaService extends  MainService
{
    private ContactMediaModal $model;
    private CustomFile $cfs;

    public function __construct()
    {
        $this->model = new ContactMediaModal();
        $this->cfs = new CustomFile();
    }

    /**
     * index function
     * @method : GET
     * @param UrlQueryParamInterface $urlQueryParam
     * @return array
     */


    public function index(UrlQueryParamInterface $urlQueryParam)
    {

        $pipeLine = $urlQueryParam->decodeQueryParam()->getPipeLine();

        return $this->model->aggregatePagination($pipeLine);


    }

    /**
     * show function
     * @method : GET with params ID
     * @param $id
     * @return array
     */
    public function show($id)
    {

        if (is_null($id)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $result = $this->model
            ->where('id', $id)->paginate(10, 'default');

        if (is_null($result)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);

        $data = [
            'data' => $result,
            'pager' => $this->model->pager->getDetails()
        ];

        return $data;
    }

    /**
     * create function
     * @method : POST
     * @param contactMediaEntity $entity
     * @throws \ReflectionException
     */
    public function create(ContactMediaEntity $entity)
    {

        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->save($entity)) {
            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }

    }

    /**
     * update function
     * @method : PUT or PATCH
     * @param $id
     * @param contactMediaEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, ContactMediaEntity $entity)
    {

        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $IsExist = $this->model->where('id', $id)->first();

        if (is_null($IsExist)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $this->cfs->removeSingleFile(ROOTPATH . $IsExist->path);


        if (!$this->model->update($id, $entity)) {

            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }


    }

    /**
     * edit function
     * @method : DELETE with params ID
     * @param $id
     * @param $foreignKey
     */
    public function delete($id, $foreignKey)
    {


        $id = ($id == 0 ? 0 : $id);

        if ($id == 0) {

            $deleteById = $this->model->where(['contact_id' => $foreignKey])->
            findAll();
            $target = array('contact_id' => $foreignKey);
        } else {
            $deleteById = $this->model->where(['id' => $id])->findAll();
            $target = array('id' => $id);
        }

        if (is_null($deleteById)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $this->model->where($target)->delete();

        foreach ($deleteById as $path) {

            $this->cfs->removeSingleFile(ROOTPATH . $path->path);
        }


    }

    public function getInsertID()
    {
        return $this->model->getInsertID();
    }

}
