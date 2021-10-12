<?php

namespace Modules\Common\Services;



use Modules\Common\Entities\ViewsMediaEntity;
use Modules\Common\Libraries\CustomFile;
use Modules\Common\Models\ViewMediaModel;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class ViewMediaService extends  MainService
{
    private ViewMediaModel $model;
    private CustomFile $cfs;

    public function __construct()
    {
        $this->model = new ViewMediaModel();
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

        $pipeLine = $urlQueryParam->setTableName('view_media')->decodeQueryParam()->getPipeLine();

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

        $result = $this->model->select('view_media.*,view_option.name')
            ->join('view_option', 'view_option.id = view_media.view_option_id', 'left')
            ->where('view_media.id', $id)->paginate(10, 'default');

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
     * @param ViewsMediaEntity $entity
     * @throws \ReflectionException
     */
    public function create(ViewsMediaEntity $entity)
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
     * @param ViewsMediaEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, ViewsMediaEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        $isExist = $this->model->where('id', $id)->first();

        if (is_null($isExist)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $this->cfs->removeSingleFile(ROOTPATH . $isExist->path);


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

            $deleteById = $this->model->where(['view_option_id' => $foreignKey])->
            findAll();
            $target = array('view_option_id' => $foreignKey);
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
