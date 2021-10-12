<?php

namespace Modules\Common\Services;


use Modules\Common\Entities\VisitorEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Models\VisitorModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class VisitorService extends MainService
{
    private VisitorModel $model;


    public function __construct()
    {
        $this->model = new VisitorModel();

    }

    /**
     * index function
     * @method : GET
     * @param UrlQueryParamInterface $urlQueryParam
     * @return array
     */
    public function index(UrlQueryParamInterface $urlQueryParam)
    {


        $this->model->keepLimitedIp(200);

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
     * @param VisitorEntity $entity
     * @throws \ReflectionException
     */
    public function create(VisitorEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        $isIpExist = $this->model->where('ip', $entity->toArray()['ip'])->countAllResults();

        if ($isIpExist == 0)

            if (!$this->model->save($entity)) {
                helper('shared');
                $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

            }


    }

    /**
     * update function
     * @method : PUT or PATCH
     * @param $id
     * @param VisitorEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, VisitorEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->update($id, $entity)) {

            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }


    }

    /**
     * edit function
     * @method : DELETE with params ID
     * @param $id
     */
    public function delete($id)
    {

        $deleteById = $this->model->find($id);

        if (is_null($deleteById)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $this->model->delete($id);

    }

    public function getInsertId()
    {
        return $this->model->getInsertID();
    }
}
