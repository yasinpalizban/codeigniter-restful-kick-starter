<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\NewsCategoryModel;
use Modules\Common\Models\NewsPostModal;
use Modules\Common\Models\NewsSubCategoryModel;
use Myth\Auth\Models\UserModel;

class NewsPostSeeder extends Seeder
{
    public function run()
    {
        $model = new NewsPostModal();

        $newsCategoryModel = new NewsCategoryModel();
        $newsSubCategoryModel = new NewsSubCategoryModel();

        $userModel = new UserModel();

        $foreignKey1 = $newsCategoryModel->select('id')->get()->getResultArray();
        $categoryId = static::faker()->randomElement($foreignKey1)['id'];
        $foreignKey2 = $newsSubCategoryModel->select('id')->where('category_id',$categoryId)->get()->getResultArray();

        $userId = $userModel->select('id')->get()->getResultArray();
        $model->insert([
            'category_id' => $categoryId,
            'sub_category_id' => static::faker()->randomElement($foreignKey2)['id'],
            'user_id' => static::faker()->randomElement($userId)['id'],
            'status' => static::faker()->boolean,
            'title' => static::faker()->jobTitle,
            'picture' => static::faker()->url,
            'body' => static::faker()->text,
            'created_at' => static::faker()->date(),

        ]);


    }
}