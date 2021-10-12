<?php namespace Modules\Auth\Database\Seeds;

use Modules\Auth\Models\UsersPermissionModel;

class UsersPermissionSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {

        $model = new UsersPermissionModel();

        $model->insertBatch([[
            'user_id' => 1,
            'permission_id' => 2,
            'actions' => '-get-post-put-delete'
        ]]);
    }
}
