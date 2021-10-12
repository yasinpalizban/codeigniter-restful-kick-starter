<?php

namespace Modules\Common\Services;


use Modules\Common\Entities\NewsPostEntity;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Models\NewsPostModal;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class NewsPostService extends MainService
{
    private NewsPostModal $model;

    public function __construct()
    {
        $this->model = new NewsPostModal();
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
            news_post.*,
        news_category.name as category,
        news_category.language ,
        news_sub_category.name as subCategory,
        users.username ,
        users.first_name as firstName,
         users.last_name as lastName',
            'join' => [
                ['table' => 'news_category',
                    'condition' => 'news_category.id = news_post.category_id',
                    'mode' => 'left'],
                ['table' => 'news_sub_category',
                    'condition' => 'news_sub_category.id = news_post.sub_category_id',
                    'mode' => 'left'],
                ['table' => 'users',
                    'condition' => 'users.id = news_post.user_id',
                    'mode' => 'left']
            ],
        ];
        $pipeLine = $urlQueryParam->setTableName('news_post')
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

        if (is_null($id)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $result = $this->model->select('news_post.*,news_category.name as category,news_category.language ,news_sub_category.name as subCategory,users.username ,users.first_name as firstName, users.last_name as lastName')
            ->join('news_category', 'news_category.id = news_post.category_id', 'left')
            ->join('news_sub_category', 'news_sub_category.id = news_post.sub_category_id', 'left')
            ->join('users', 'users.id = news_post.user_id', 'left')
            ->where('news_post.id', $id)->paginate(1, 'default');;
        $result = $this->model->appendChildrenRows($result);

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
     * @param NewsPostEntity $entity
     * @throws \ReflectionException
     */
    public function create(NewsPostEntity $entity)
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
     * @param NewsPostEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, NewsPostEntity $entity)
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
