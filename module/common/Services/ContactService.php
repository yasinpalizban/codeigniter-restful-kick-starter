<?php


namespace Modules\Common\Services;

use CodeIgniter\HTTP\Exceptions\HTTPException;
use Modules\Common\Entities\ContactEntity;
use Modules\Common\Models\ContactModal;
use CodeIgniter\HTTP\ResponseInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class ContactService extends MainService
{
    private ContactModal $model;

    public function __construct()
    {
        $this->model = new ContactModal();
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

        $result = $this->model->where('id', $id)->paginate(1, 'default');

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
     * @param contactEntity $entity
     * @throws \ReflectionException
     */
    public function create(ContactEntity $entity)
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
     * @param contactEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, ContactEntity $entity)
    {
        if (is_null($entity)) throw new HttpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->update($id, $entity)) {

            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }

        $updated = $this->model->find($id);


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

        if (is_null($deleteById)) throw new HttpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);

        $this->model->delete($id);


    }

    public function getInsertId()
    {
        return $this->model->getInsertID();
    }
}
