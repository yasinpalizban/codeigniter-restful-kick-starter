<?php

namespace Modules\Common\Services;


use Modules\Common\Entities\NewsSubCategoryEntity;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Models\NewsSubCategoryModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class NewsSubCategoryService extends MainService
{
    private NewsSubCategoryModel $model;

    public function __construct()
    {
        $this->model = new NewsSubCategoryModel();
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
            'select' => 'news_sub_category.*,news_category.name as category,news_category.language',
            'join' => [
                ['table' => 'news_category',
                    'condition' => 'news_category.id = news_sub_category.category_id',
                    'mode' => 'left']
            ],


        ];


        $pipeLine = $urlQueryParam->setTableName('news_sub_category')
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

        $result = $this->model->select('news_sub_category.*,news_category.name as category,news_category.language')
            ->join('news_category', 'news_category.id = news_sub_category.category_id', 'left')
            ->where('news_sub_category.id', $id)->paginate(1, 'default');
        if (is_null($result)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);

        $data = ['data' => $result,
            'pager' => $this->model->pager->getDetails()
        ];
        return $data;

    }

    /**
     * create function
     * @method : POST
     * @param NewsSubCategoryEntity $entity
     * @throws \ReflectionException
     */
    public function create(NewsSubCategoryEntity $entity)
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
     * @param NewsSubCategoryEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, NewsSubCategoryEntity $entity)
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
