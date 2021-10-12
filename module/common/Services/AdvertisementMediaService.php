<?php

namespace Modules\Common\Services;


use CodeIgniter\HTTP\Exceptions\HTTPException;
use Modules\Common\Entities\AdvertisementMediaEntity;
use Modules\Common\Libraries\CustomFile;
use Modules\Common\Models\AdvertisementMediaModel;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class AdvertisementMediaService extends MainService
{
    private AdvertisementMediaModel $model;
    private CustomFile $cfs;

    public function __construct()
    {
        $this->model = new AdvertisementMediaModel();
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

        $result = $this->model->where('id', $id)->paginate(1, 'default');

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
     * @param AdvertisementMediaEntity $entity
     * @throws \ReflectionException
     */
    public function create(AdvertisementMediaEntity $entity)
    {


        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->save($entity)) {
            helper('shared');
            $message = lang('Shared.api.reject') . " \n " . serializeMessages($this->model->errors());
            $this->httpException($message, ResponseInterface::HTTP_BAD_REQUEST);

        }


    }

    /**
     * update function
     * @method : PUT or PATCH
     * @param $id
     * @param AdvertisementMediaEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, AdvertisementMediaEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        $isExist = $this->model->where('id', $id)->first();

        if (is_null($isExist)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);

        $this->cfs->removeSingleFile(ROOTPATH . $isExist->path);


        if (!$this->model->update($id, $entity)) {

            helper('shared');
            $message = lang('Shared.api.reject') . " \n " . serializeMessages($this->advertisementOptionModel->errors());
            $this->httpException($message, ResponseInterface::HTTP_BAD_REQUEST);

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

            $deleteById = $this->model->where(['advertisement_id' => $foreignKey])->
            findAll();
            $target = array('advertisement_id' => $foreignKey);
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

    public function getInsertId()
    {
        return $this->model->getInsertID();
    }
}
