<?php namespace Modules\Common\Services;



use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Libraries\CustomFile;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;

class  ProfileService extends MainService
{
    private \Myth\Auth\Models\UserModel $model;
    private CustomFile $cfs;
    private string $userId;

    public function __construct()
    {
        $this->userId = "";
        $this->model = new  \Myth\Auth\Models\UserModel();
        $this->cfs = new CustomFile();
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
        $result = $this->model->select('
         users.id,
      users.email,
      users.username,
      users.first_name,
      users.last_name,
      users.image,
      users.gender,
      users.birthday,
      users.country,
      users.city,
      users.address,
      users.phone,
      users.status_message,
      users.status,
      users.active,
      users.created_at,
      users.updated_at,
      users.deleted_at
        ')->find($this->userId);

        $data = ['data' => $result];
        return $data;
    }

    /**
     * update function
     * @method : Post
     * @param $id
     * @param \Myth\Auth\Entities\User $entity
     * @throws \ReflectionException
     */
    public function update($id, \Myth\Auth\Entities\User $entity)
    {

        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        $isExist = $this->model->where('id', $id)->first();

        if (is_null($isExist)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);
        if ($isExist->image != 'public/upload/profile/default-avatar.jpg') {
            $this->cfs->removeSingleFile(ROOTPATH . $isExist->image);
        }


        if (!$this->model->update($id, $entity)) {
            helper('shared');

            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }


    }


}
