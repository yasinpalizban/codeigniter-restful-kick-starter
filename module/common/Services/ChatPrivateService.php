<?php


namespace Modules\Common\Services;


use Modules\Common\Entities\ChatPrivateEntity;
use Modules\Common\Models\ChatPrivateModel;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class  ChatPrivateService extends MainService
{
    private ChatPrivateModel $model;
    private string $userId;

    public function __construct()
    {
        $this->userId = "";
        $this->model = new ChatPrivateModel();
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
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


        $pipeLine = ['where' => [
            'user_receiver_id' => $urlQueryParam->getForeignKey(),
            'user_sender_id' => $this->userId],
            'orWhere' => [
                'user_receiver_id' => $this->userId,
                'user_sender_id' => $urlQueryParam->getForeignKey()]
        ];
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
     * @param ChatPrivateEntity $entity
     * @throws \ReflectionException
     */
    public function create(ChatPrivateEntity $entity)
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
     * @param ChatPrivateEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, ChatPrivateEntity $entity)
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
