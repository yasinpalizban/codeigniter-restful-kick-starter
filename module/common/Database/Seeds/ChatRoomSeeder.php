<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\ChatRoomModel;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Models\UserModel;

class ChatRoomSeeder extends Seeder
{
    public function run()
    {
        $model = new ChatRoomModel();
        $userModel = new UserModel();
        $groupModel = new GroupModel();
        $userId = $userModel->select('id')->get()->getResultArray();
        $groupId = $groupModel->select('id')->get()->getResultArray();
        $model->insert([
            'user_id' => static::faker()->randomElement($userId)['id'],
            'group_id' => static::faker()->randomElement($groupId)['id'],
            'message' => static::faker()->text(100),
            'status' => static::faker()->boolean,
            'created_at' => static::faker()->date()

        ]);
    }
}