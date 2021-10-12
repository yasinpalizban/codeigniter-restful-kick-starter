<?php

namespace Modules\Common\Services;



use Modules\Common\Entities\NewsCategoryEntity;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Models\NewsCategoryModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class NewsCategoryService  extends  MainService
{
    private  NewsCategoryModel $model;

    public function __construct()
    {
        $this->model = new NewsCategoryModel();
    }

    /**
     * index function
     * @method : GET
     */
    public function index(UrlQueryParamInterface $urlQueryParam)
    {

        $pipeLine = $urlQueryParam->decodeQueryParam()->getPipeLine();

        return $this->model->aggregatePagination($pipeLine);

    }

    /**
     * show function
     * @method : GET with params ID
     */
    public function show($id)
    {


        if (is_null($id)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $result = $this->model->where('id', $id)->paginate(1, 'default');
        if (is_null($result)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);

        $data = ['data' => $result,
            'pager' => $this->model->pager->getDetails()
        ];
        return $data;

    }

    /**
     * create function
     * @method : POST
     */
    public function create(NewsCategoryEntity $entity)
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
     */
    public function update($id, NewsCategoryEntity $entity)
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
