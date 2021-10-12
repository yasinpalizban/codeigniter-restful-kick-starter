<?php namespace Modules\Auth\Models;

use CodeIgniter\Model;
use Modules\Auth\Entities\GroupsPermissionEntity;
use Modules\Shared\Models\Aggregation;

class GroupsPermissionModel extends  Aggregation
{
    protected $table = 'auth_groups_permissions';

    protected $returnType = GroupsPermissionEntity::class;
    protected $allowedFields = [
        'group_id',
        'permission_id',
        'actions'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [

        'actions' => 'required|max_length[255]',
        'group_id' => 'required',
        'permission_id' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function permissionsOfGroup(string $groupId): array
    {
        return $this->db->table('auth_groups_users')
            ->select('
            auth_groups_permissions.id,
            auth_groups_permissions.group_id as groupId,
            auth_groups_permissions.permission_id as permissionId,
            auth_groups_permissions.actions,
             auth_permissions.name as permission
             ')
            ->join('auth_groups_permissions', 'auth_groups_permissions.group_id = auth_groups_users.group_id', 'inner')
            ->join('auth_permissions', 'auth_permissions.id = auth_groups_permissions.permission_id', 'inner')
            ->where('auth_groups_permissions.group_id', $groupId)
            ->get()
            ->getResultObject();
    }

}
