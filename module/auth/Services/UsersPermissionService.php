<?php


namespace Modules\Auth\Services;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Auth\Entities\UsersPermissionEntity;
use Modules\Auth\Models\UsersPermissionModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class UsersPermissionService extends MainService
{
    private UsersPermissionModel $model;

    public function __construct()
    {
        $this->model = new UsersPermissionModel();
    }

    /**
     * index function
     * @method : GET
     */
    public function index(UrlQueryParamInterface $urlQueryParam)
    {


        $pipeLine = [
            'select' => 'auth_users_permissions.id,
        auth_users_permissions.actions,
         auth_users_permissions.user_id as userId ,
         auth_users_permissions.permission_id as permissionId ,
        users.username,
        users.first_name as firstName,
        users.last_name as lastName,
       auth_permissions.name as permission',
            'join' => [
                ['table' => 'users',
                    'condition' => 'users.id = auth_users_permissions.user_id',
                    'mode' => 'left'],
                ['table' => 'auth_permissions',
                    'condition' => 'auth_permissions.id = auth_users_permissions.permission_id',
                    'mode' => 'left']
            ]
        ];

        $pipeLine = $urlQueryParam->setTableName('auth_users_permissions')
            ->decodeQueryParam()
            ->getPipeLine($pipeLine);

        return $this->model->aggregatePagination($pipeLine);


    }

    /**
     * show function
     * @method : GET with params ID
     */
    public function show($id)
    {
        if (is_null($id)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $result = $this->model->select(
            '
        auth_users_permissions.*,
        users.username,
        users.first_name as firstName,
        users.last_name as lastName,
        auth_permissions.name as permission
        

        ')
            ->join('users', 'users.id = auth_users_permissions.user_id', 'left')
            ->join('auth_permissions', 'auth_permissions.id = auth_users_permissions.permission_id', 'left')
            ->where('auth_users_permissions.id', $id)
            ->paginate(1, 'default');
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
     * @param UsersPermissionEntity $entity
     * @throws \ReflectionException
     */
    public function create(UsersPermissionEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $isExist = $this->model->where([
            'user_id' => $entity->groupId,
            'permission_id' => $entity->permissionId
        ])->first();

        if (!is_null($isExist)) $this->httpException(lang('Shared.api.already'), ResponseInterface::HTTP_NOT_FOUND);


        if (!$this->model->save($entity)) {
            helper('shared');

            $this->httpException(lang('Shared.api.reject'), ResponseInterface::HTTP_BAD_REQUEST, serializeMessages($this->model->errors()));

        }


    }

    /**
     * update function
     * @method : PUT or PATCH
     * @param $id
     * @param UsersPermissionEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, UsersPermissionEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $isExist = $this->model->where([
            'user_id' => $entity->userId,
            'permission_id' => $entity->permissionId
        ])->first();

        if (!is_null($isExist) && $isExist->id != $id) $this->httpException(lang('Shared.api.already'), ResponseInterface::HTTP_NOT_FOUND);


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
}
