<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\RequestCategoryModel;
use Modules\Common\Models\RequestPostModal;
el;
use Myth\Auth\Models\UserModel;

class RequestPostSeeder extends Seeder
{
    public function run()
    {
        $model = new RequestPostModal();

        $requestCategoryModel = new RequestCategoryModel();
        $userModel = new UserModel();

        $foreignKey = $requestCategoryModel->select('id')->get()->getResultArray();
        $userId = $userModel->select('id')->get()->getResultArray();
        $model->insert([
            'category_id' => static::faker()->randomElement($foreignKey)['id'],
            'user_id' => static::faker()->randomElement($userId)['id'],
            'status' => static::faker()->boolean,
            'title'=>static::faker()->title,
            'body' => static::faker()->text,
            'created_at' => static::faker()->date(),

        ]);

    }
}