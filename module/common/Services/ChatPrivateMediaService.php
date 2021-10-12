<?php

namespace Modules\Common\Services;


use Modules\Common\Entities\chatPrivateMediaEntity;
use Modules\Common\Libraries\CustomFile;
use Modules\Common\Models\ChatPrivateMediaModel;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class ChatPrivateMediaService  extends  MainService
{
    private ChatPrivateMediaModel $model;
    private CustomFile $cfs;

    public function __construct()
    {
        $this->model = new ChatPrivateMediaModel();
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

        $result = $this->model
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
     * @param ChatPrivateMediaEntity $entity
     * @throws \ReflectionException
     */
    public function create(ChatPrivateMediaEntity $entity)
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
     * @param ChatPrivateMediaEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, ChatPrivateMediaEntity $entity)
    {

        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $updateById = $this->model->where('id', $id)->first();

        if (is_null($updateById)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $this->cfs->removeSingleFile(ROOTPATH . $updateById->path);


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

            $deleteById = $this->model->where(['chat_private_id' => $foreignKey])->
            findAll();
            $target = array('chat_private_id' => $foreignKey);
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
