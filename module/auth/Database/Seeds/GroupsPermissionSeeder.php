<?php namespace Modules\Auth\Database\Seeds;

use Modules\Auth\Models\GroupsPermissionModel;

class GroupsPermissionSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $model = new GroupsPermissionModel();

        $model->insertBatch([[
            'group_id' => 1,
            'permission_id' => 1,
            'action' => '-get-post-put-delete'
        ]]);

    }
}
