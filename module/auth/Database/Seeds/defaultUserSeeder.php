<?php namespace Modules\Auth\Database\Seeds;

use Modules\Auth\Config\ModuleAuthConfig;
use Myth\Auth\Models\UserModel;

class defaultUserSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {

        $model = new UserModel();
        $authConfig = new ModuleAuthConfig();

        $model->insert([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'phone' => '0918000',
            "active" => "1",
            //default password is == pass
            "password_hash" => '$2y$10$7i2pxCY7hvp7BQfpkVAXgulJkC/f8i1g71YQ/TRBuJvhPsKLAsAt6',
            "image" => $authConfig->defualtUserProfile,
            "first_name"=>"admin",
            "last_name"=>"admin",
        ]);
    }
}
