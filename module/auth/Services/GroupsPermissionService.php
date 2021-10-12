<?php


namespace Modules\Auth\Services;


use CodeIgniter\HTTP\ResponseInterface;
use Modules\Auth\Entities\GroupsPermissionEntity;
use Modules\Auth\Models\GroupsPermissionModel;
use Modules\Shared\Interfaces\UrlQueryParamInterface;
use Modules\Shared\Libraries\MainService;


class GroupsPermissionService extends MainService
{
    private GroupsPermissionModel $model;

    public function __construct()
    {
        $this->model = new GroupsPermissionModel();
    }

    /**
     * index function
     * @method : GET
     */
    public function index(UrlQueryParamInterface $urlQueryParam)
    {


        $pipeLine = ['select' => '
        auth_groups_permissions.id,
        auth_groups_permissions.actions,
         auth_groups_permissions.group_id as groupId ,
         auth_groups_permissions.permission_id as permissionId ,
       auth_groups.name as group,
       auth_permissions.name as permission',
            'join' => [
                ['table' => 'auth_groups',
                    'condition' => 'auth_groups.id = auth_groups_permissions.group_id',
                    'mode' => 'left'],
                ['table' => 'auth_permissions',
                    'condition' => 'auth_permissions.id = auth_groups_permissions.permission_id',
                    'mode' => 'left']
            ]
        ];


        $pipeLine = $urlQueryParam->setTableName('auth_groups_permissions')
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
        auth_groups_permissions.*,
        auth_groups.name as group,
        auth_permissions.name as permission
        

        ')
            ->join('auth_groups', 'auth_groups.id = auth_groups_permissions.group_id', 'left')
            ->join('auth_permissions', 'auth_permissions.id = auth_groups_permissions.permission_id', 'left')
            ->where('auth_groups_permissions.id', $id)
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
     * @param GroupsPermissionEntity $entity
     * @throws \ReflectionException
     */
    public function create(GroupsPermissionEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);

        $isExist = $this->model->where([
            'group_id' => $entity->groupId,
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
     * @param GroupsPermissionEntity $entity
     * @throws \ReflectionException
     */
    public function update($id, GroupsPermissionEntity $entity)
    {
        if (is_null($entity)) $this->httpException(lang('Shared.api.validation'), ResponseInterface::HTTP_NOT_FOUND);


        $isExist = $this->model->where([
            'group_id' => $entity->groupId,
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
