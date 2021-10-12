<?php namespace Modules\Auth\Models;

use CodeIgniter\Model;
use Modules\Auth\Entities\UsersPermissionEntity;
use Modules\Shared\Models\Aggregation;

class UsersPermissionModel extends Aggregation
{
    protected $table = 'auth_users_permissions';

    protected $returnType = UsersPermissionEntity::class;
    protected $allowedFields = [
        'user_id',
        'permission_id',
        'actions'
    ];

    protected $useTimestamps = false;

    protected $validationRules = [

        'actions' => 'required|max_length[255]',
        'user_id' => 'required',
        'permission_id' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;


    public function permissionsOfUser(string $userId): array
    {
        return $this->db->table('auth_users_permissions')
            ->select('auth_users_permissions.id,
            auth_users_permissions.user_id as userId,
            auth_users_permissions.permission_id as permissionsId,
            auth_users_permissions.actions,
             auth_permissions.name as permission')
            ->join('auth_permissions', 'auth_permissions.id = permission_id', 'inner')
            ->where('user_id', $userId)
            ->get()
            ->getResultObject();
    }

}
