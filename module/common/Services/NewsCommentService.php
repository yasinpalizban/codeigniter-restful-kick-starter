<?php

namespace Modules\Common\Services;


use Modules\Common\Entities\newsCommentEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Models\NewsCommentModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;

class NewsCommentService extends MainService
{
    private NewsCommentModel $model;

    public function __construct()
    {
        $this->model = new NewsCommentModel();
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
            'select' => 'news_comment.*,users.username',
            'join' => [
                ['table' => 'users',
                    'condition' => 'news_comment.user_id = users.id',
                    'mode' => 'left']
            ],


        ];


        $pipeLine = $urlQueryParam->setTableName('news_comment')
            ->decodeQueryParam()->getPipeLine($pipeLine);

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

        $result = $this->model->select('news_comment.*, users.username')
            ->join('users', 'news_comment.user_id = users.id', 'left')
            ->where('news_comment.id', $id)->paginate(1, 'default');


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
     * @param NewsCommentEntity $entity
     * @return int|string
     * @throws \ReflectionException
     */
    public function create(NewsCommentEntity $entity)
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
     * @param NewsCommentEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, NewsCommentEntity $entity)
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

    public function getInsertID()
    {
        return $this->model->getInsertID();
    }
}
