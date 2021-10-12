<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\RequestCategoryModel;
use Modules\Common\Models\RequestPostModal;
use Modules\Common\Models\RequestReplyModel;
use Modules\Common\Models\UsersModel;
use Myth\Auth\Models\UserModel;

class  RequestReplySeeder extends Seeder
{
    public function run()
    {
        $model = new RequestReplyModel();

        $requestPostModel = new RequestPostModal();
        $userModel = new UserModel();

        $foreignKey = $requestPostModel->select('id')->get()->getResultArray();
        $userId = $userModel->select('id')->get()->getResultArray();
        $model->insert([
            'post_id' => static::faker()->randomElement($foreignKey)['id'],
            'user_id' => static::faker()->randomElement($userId)['id'],
            'message' => static::faker()->text,
            'created_at' => static::faker()->date(),
            'reply_id'=>0

        ]);

    }
}