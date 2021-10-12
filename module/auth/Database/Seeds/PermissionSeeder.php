<?php namespace Modules\Auth\Database\Seeds;

use Myth\Auth\Authorization\PermissionModel;

class PermissionSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $model = new PermissionModel();

        $model->insertBatch([[
            'name' => 'user',
            'description' => 'manage user',
            'active'=>0
        ], [
            'name' => 'setting',
            'description' => 'manage setting',
            'active'=>0
        ]]);
    }
}
