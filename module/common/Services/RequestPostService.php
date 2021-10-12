<?php

namespace Modules\Common\Services;


use CodeIgniter\HTTP\Exceptions\HTTPException;
use Modules\Common\Entities\RequestPostEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Models\RequestPostModal;

use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class RequestPostService extends MainService
{
    private RequestPostModal $model;
    private string $userId;
    private string $userGroupName;

    public function __construct()
    {
        $this->userId = "";
        $this->userGroupName = "";
        $this->model = new RequestPostModal();
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }


    public function setUserGroupName(string $userGroupName): void
    {
        $this->userGroupName = $userGroupName;
    }

    /**
     * index function
     * @method : GET
     * @param UrlQueryParamInterface $urlQueryParam
     * @return array
     */
    public function index(UrlQueryParamInterface $urlQueryParam)
    {

        $pipeLine = [
            'select' => '
            request_post.*,
        request_category.name as category ,
        request_category.language ,
        users.username ,
        users.first_name as firstName,
         users.last_name as lastName',
            'join' => [
                ['table' => 'request_category',
                    'condition' => 'request_category.id = request_post.category_id',
                    'mode' => 'left'],
                ['table' => 'users',
                    'condition' => 'request_post.user_id = users.id',
                    'mode' => 'left']
            ]
        ];
        if ($this->userGroupName == 'member') {
            $pipeLine['where'] = ['user_id' => $this->userId];
        }
        $pipeLine = $urlQueryParam->setTableName('request_post')
            ->decodeQueryParam()->getPipeLine($pipeLine);

        $data = $this->model->aggregatePagination($pipeLine);


        $data['data'] = $this->model->appendChildrenRows($data['data']);

        return $data;


    }

    /**
     * show function
     * @method : GET with params ID
     * @param $id
     * @return array
     */
    public function show($id)
    {

        if (is_null($id)) throw new HttpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $result = $this->model->select('request_post.*,request_category.name as category ,request_category.language ,users.username ,users.first_name as firstName, users.last_name as LastName')
            ->join('request_category', 'request_category.id = request_post.category_id', 'left')
            ->join('users', 'users.id = request_post.user_id', 'left')
            ->where('request_post.id', $id)->paginate(1, 'default');
        $result = $this->model->appendChildrenRows($result);

        if (is_null($result)) throw new HttpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);

        $data = [
            'data' => $result,
            'pager' => $this->model->pager->getDetails()
        ];
        return $data;
    }

    /**
     * create function
     * @method : POST
     * @param RequestPostEntity $entity
     * @throws \ReflectionException
     */
    public function create(RequestPostEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->save($entity)) {
            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }


    }

    /**
     * update function
     * @method : PUT or PATCH
     * @param $id
     * @param RequestPostEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, RequestPostEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        $isExist = $this->model->where('id', $id)->first();

        if (is_null($isExist)) throw new HttpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


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

        if (is_null($deleteById)) throw new HttpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);

        $this->model->delete($id);


    }

    public function getInsertId()
    {
        return $this->model->getInsertID();
    }
}
