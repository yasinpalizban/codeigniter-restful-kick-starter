<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\NewsCommentModel;
use Modules\Common\Models\NewsPostModal;
use Myth\Auth\Models\UserModel;


class  NewsCommentSeeder extends Seeder
{
    public function run()
    {
        $model = new NewsCommentModel();

        $newsPostModel = new NewsPostModal();
        $userModel = new UserModel();

        $foreignKey = $newsPostModel->select('id')->get()->getResultArray();
        $userId = $userModel->select('id')->get()->getResultArray();

        $model->insert([
            'post_id' => static::faker()->randomElement($foreignKey)['id'],
            'user_id' => static::faker()->randomElement($userId)['id'],
            'message' => static::faker()->text,
            'reply_id'=>0,
            'created_at' => static::faker()->date(),

        ]);
    }
}