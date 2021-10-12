<?php

namespace Modules\Common\Services;


use Modules\Common\Entities\RequestReplyEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Models\RequestReplyModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;

class RequestReplyService extends MainService
{
    private RequestReplyModel $model;

    public function __construct()
    {
        $this->model = new RequestReplyModel();
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
            'select' => 'request_reply.*,users.username',
            'join' => [
                ['table' => 'request_post',
                    'condition' => 'request_reply.post_id = request_post.id',
                    'mode' => 'left'],
                ['table' => 'users',
                    'condition' => 'request_reply.user_id = users.id',
                    'mode' => 'left']
            ]
        ];
        $pipeLine = $urlQueryParam->setTableName('request_reply')
            ->decodeQueryParam()->getPipeLine($pipeLine);

        $data = $this->model->aggregatePagination($pipeLine);


        $data['data'] = $this->model->appendReplyRows($data['data']);

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

        if (is_null($id)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $result = $this->model->select('request_reply.*, users.username')
            ->join('users', 'request_reply.user_id = users.id', 'left')
            ->where('request_reply.id', $id)->paginate(1, 'default');


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
     * @param RequestReplyEntity $entity
     * @return int|string
     * @throws \ReflectionException
     */
    public function create(RequestReplyEntity $entity)
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
     * @param RequestReplyEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, RequestReplyEntity $entity)
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
     * @param $foreignKey
     */
    public function delete($id, $foreignKey)
    {


        $id = ($id == 0 ? 0 : $id);

        if ($id == 0) {

            $deleteById = $this->model->where(['post_id' => $foreignKey])->
            findAll();
            $target = array('post_id' => $foreignKey);
        } else {
            $deleteById = $this->model->where(['id' => $id])->findAll();
            $target = array('id' => $id);
        }
        if (is_null($deleteById)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $this->model->where($target)->delete();


    }

    public function getInsertId()
    {
        return $this->model->getInsertID();
    }
}
