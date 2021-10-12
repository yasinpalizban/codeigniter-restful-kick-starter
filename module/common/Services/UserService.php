<?php namespace Modules\Common\Services;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Common\Libraries\CustomFile;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;

class  UserService extends MainService
{
    private \Myth\Auth\Models\UserModel $userModel;
    private \Myth\Auth\Authorization\GroupModel $groupModel;
    private CustomFile $cfs;

    public function __construct()
    {
        $this->userModel = new  \Myth\Auth\Models\UserModel();
        $this->groupModel = new \Myth\Auth\Authorization\GroupModel();
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



        $pipeLine = [
            'select' => '
            users.id,
      users.email,
      users.username ,
      users.first_name as firstName,
      users.last_name as lastName,
      users.image,
      users.gender,
      users.birthday,
      users.country,
      users.city,
      users.address,
      users.phone,
      users.status_message as statusMessage,
      users.status,
      users.active ,
      users.created_at as createdAt,
      users.updated_at as updatedAt,
      users.deleted_at as deletedAt',
            'auth_groups.name as group',
            'join' => [
                ['table' => 'auth_groups_users',
                    'condition' => 'auth_groups_users.group_id = auth_groups.id',
                    'mode' => 'right'],
                ['table' => 'users',
                    'condition' => 'auth_groups_users.user_id = users.id',
                    'mode' => 'left']
            ]
        ];
        $pipeLine = $urlQueryParam->setTableName('users')->decodeQueryParam()->getPipeLine($pipeLine);

        return $this->groupModel->aggregatePagination($pipeLine);


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


        $group = $this->groupModel->getGroupsForUser($id);
        $result = $this->groupModel->select(' 
          users.email,
      users.username ,
      users.first_name as firstName,
      users.last_name as lastName,
      users.image,
      users.gender,
      users.birthday,
      users.country,
      users.city,
      users.address,
      users.phone,
      users.status_message as statusMessage,
      users.status,
      users.active ,
      users.created_at as createdAt,
      users.updated_at as updatedAt,
      users.deleted_at as deletedAt,
        auth_groups.name as group')
            ->join('auth_groups_users', 'auth_groups_users.group_id = auth_groups.id', 'left')
            ->join('users', 'auth_groups_users.user_id = users.id', 'left')
            ->where('auth_groups.id', $group[0]['group_id'])
            ->where('users.id', $id)
            ->paginate(1, 'default');


        if (is_null($result)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $data = [
            'data' => $result,
            'pager' => $this->groupModel->pager->getDetails()
        ];


        return $data;


    }

    /**
     * create function
     * @method : POST
     * @param \Myth\Auth\Entities\User $entity
     */
    public function create(\Myth\Auth\Entities\User $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $this->userModel->withGroup($entity->toArray()['group']);


        if (!$this->userModel->save($entity)) {
            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        }


    }

    /**
     * update function
     * @method : PUT or PATCH
     * @param $id
     * @param \Myth\Auth\Entities\User $entity
     */
    public function update($id, \Myth\Auth\Entities\User $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        $isExist = $this->userModel->where('id', $id)->first();

        if (is_null($isExist)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);
        if ($isExist->image != 'public/upload/profile/default-avatar.jpg') {
            $this->cfs->removeSingleFile(ROOTPATH . $isExist->image);
        }

        if (isset($entity->toArray()['group'])) {
            $this->groupModel->removeUserFromAllGroups($id);
            $groupId = $this->groupModel->where('name', $entity->group)->find();
            $this->groupModel->addUserToGroup($id, $groupId[0]->id);

        }


        if (!$this->userModel->update($id, $entity)) {

            helper('shared');
            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->userModel->errors()));

        };


    }

    /**
     * edit function
     * @method : DELETE with params ID
     * @param $id
     */
    public function delete($id)
    {


        $deleteById = $this->userModel->find($id);

        if (is_null($deleteById)) $this->httpException(lang('Shared.api.exist'), ResponseInterface::HTTP_NOT_FOUND);


        $this->groupModel->removeUserFromAllGroups($id);
        $this->userModel->delete($id);
    }

    public function getInsertId()
    {
        return $this->userModel->getInsertID();
    }
}
