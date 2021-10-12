<?php


namespace Modules\Auth\Services;


use Modules\Auth\Entities\GroupEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;
use Myth\Auth\Authorization\GroupModel;


class GroupService extends  MainService
{
    private  GroupModel $model;

    public function __construct()
    {
        $this->model = new  GroupModel();
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
     * @param GroupEntity $entity
     * @throws \ReflectionException
     */
    public function create(GroupEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->save($entity)) {
            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST,serializeMessages($this->model->errors()));

        }



    }

    /**
     * update function
     * @method : PUT or PATCH
     * @param $id
     * @param GroupEntity $entity
     * @throws \ReflectionException
     */
    public function update($id , GroupEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);



        if (!$this->model->update($id, $entity)) {

            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST,serializeMessages($this->model->errors()));

        }


    }

    /**
     * edit function
     * @method : DELETE with params ID
     * @param $id
     */
    public function delete($id )
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
