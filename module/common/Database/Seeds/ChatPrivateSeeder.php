<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\ChatPrivateModel;

use Myth\Auth\Models\UserModel;

class ChatPrivateSeeder extends Seeder
{
    public function run()
    {
        $model = new ChatPrivateModel();
        $userModel = new UserModel();

        $userId = $userModel->select('id')->get()->getResultArray();
        $model->insert([
            'user_sender_id' => static::faker()->randomElement($userId)['id'],
            'user_receiver_id' => static::faker()->randomElement($userId)['id'],
            'message' => static::faker()->text(100),
            'status' => static::faker()->boolean,
            'created_at' => static::faker()->date()

        ]);
    }
}