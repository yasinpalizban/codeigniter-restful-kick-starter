<?php namespace Modules\Auth\Database\Seeds;

use Modules\Auth\Config\ModuleAuthConfig;
use Myth\Auth\Models\UserModel;

class UsersSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $model = new UserModel();
        $authConfig = new ModuleAuthConfig();

        $model->insert([
            'username' => static::faker()->userName,
            'email' => static::faker()->email,
            'phone' => static::faker()->phoneNumber,
            "active" => static::faker()->boolean,
            //default password is == pass
            "password_hash" => '$2y$10$7i2pxCY7hvp7BQfpkVAXgulJkC/f8i1g71YQ/TRBuJvhPsKLAsAt6',
            "first_name" => static::faker()->firstName,
            "last_name" => static::faker()->lastName,
            "country" => static::faker()->country,
            "city" => static::faker()->city,
            "address" => static::faker()->address,
            "gender" => static::faker()->boolean,
            "image" => $authConfig->defualtUserProfile,
        ]);
        $this->db->table('auth_groups_users')
            ->insert([
                'group_id' => '4',
                'user_id' => $model->getInsertID()
            ]);

    }
}
