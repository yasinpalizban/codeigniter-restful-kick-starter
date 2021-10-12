<?php namespace Modules\Auth\Models;

use CodeIgniter\Model;
use Modules\Auth\Entities\GroupsPermissionEntity;
use Modules\Shared\Models\Aggregation;

class  GroupsUsersModel extends Aggregation
{
    protected $table = 'auth_groups_users';

    protected $returnType = GroupsPermissionEntity::class;
    protected $allowedFields = [
        'group_id',
        'user_id',

    ];

    protected $useTimestamps = false;

    protected $validationRules = [

        'group_id' => 'required',
        'user_id' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

}
