<?php namespace Modules\Auth\Database\Seeds;

class UsersGroupsSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {


        $this->db->table('auth_groups_users')
            ->insert([
                'group_id' => '1',
                'user_id' => '1'
            ]);

    }
}
