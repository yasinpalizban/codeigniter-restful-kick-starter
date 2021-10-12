<?php namespace Modules\Auth\Database\Seeds;

use Myth\Auth\Authorization\GroupModel;

class GroupSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $model = new GroupModel();

        $model->insertBatch([[
            'name' => 'admin',
            'description' => 'admin'
        ], [
            'name' => 'coworker',
            'description' => 'coworker'
        ], [
            'name' => 'blogger',
            'description' => 'blogger'
        ], [
            'name' => 'member',
            'description' => 'member'
        ]]);

        $this->db->table('auth_groups_users')
            ->insert([
                'group_id' => '1',
                'user_id' => '1'
            ]);

    }
}
