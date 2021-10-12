<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\AdvertisementMediaModel;
use Modules\Common\Models\AdvertisementModel;
use Modules\Common\Models\ChatPrivateMediaModel;
use Modules\Common\Models\ChatPrivateModel;

class ChatPrivateMediaSeeder extends Seeder
{
    public function run()
    {
        $model = new ChatPrivateMediaModel();

        $chatPrivateModel = new ChatPrivateModel();
        $foreignKey = $chatPrivateModel->select('id')->get()->getResultArray();
        $path = static::faker()->image('./upload/chat_private', 600, 400);
        $path = "public" . substr($path, 1);
        $path = str_replace('\\', '/', $path);
        $model->insert([
            'chat_private_id' => static::faker()->randomElement($foreignKey)['id'],
            'path' => $path

        ]);
    }
}