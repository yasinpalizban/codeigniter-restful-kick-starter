<?php


namespace Modules\Common\Services;


use Modules\Common\Entities\ChatRoomEntity;

use Modules\Common\Models\ChatRoomModel;

use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class ChatRoomService extends MainService
{
    private ChatRoomModel $model;
    private string $groupId;


    public function __construct()
    {
        $this->groupId = "1";
        $this->model = new  ChatRoomModel();
    }

    public function setGroupId(string $groupId): void
    {
        $this->groupId = $groupId;
    }

    /**
     * index function
     * @method : GET
     * @param UrlQueryParamInterface $urlQueryParam
     * @return array
     */
    public function index(UrlQueryParamInterface $urlQueryParam)
    {


        $this->model->dropLastWeekChats();

        $pipeLine = ['where' => ['group_id' => $this->groupId]];
        $pipeLine = $urlQueryParam->decodeQueryParam()->getPipeLine($pipeLine);
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
     * @param ChatRoomEntity $entity
     * @return bool
     * @throws \ReflectionException
     */
    public function create(ChatRoomEntity $entity)
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
     * @param ChatRoomEntity $entity
     * @return bool
     * @throws \ReflectionException
     */
    public function update($id, ChatRoomEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->update($id, $entity)) {

            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }
        $updated = $this->model->find($id);

        $updated->media = $this->model->appendMediaRows($id);

        return $updated;

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

    public function getInsertID()
    {
        return $this->model->getInsertID();
    }


}
