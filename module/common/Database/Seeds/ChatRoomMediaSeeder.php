<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\ChatRoomMediaModel;
use Modules\Common\Models\ChatRoomModel;

class ChatRoomMediaSeeder extends Seeder
{
    public function run()
    {
        $model = new ChatRoomMediaModel();

        $chatPrivateModel = new ChatRoomModel();
        $foreignKey = $chatPrivateModel->select('id')->get()->getResultArray();
        $path= static::faker()->image('./upload/chat_room', 600, 400);
        $path = "public" . substr($path, 1);
        $path = str_replace('\\', '/', $path);
        $model->insert([
            'chat_room_id' => static::faker()->randomElement($foreignKey)['id'],
            'path' =>$path

        ]);
    }
}