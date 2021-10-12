<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\NewsCategoryModel;
use Modules\Common\Models\NewsSubCategoryModel;

class NewsSubCategorySeeder extends Seeder
{
    public function run()
    {
        $model = new NewsSubCategoryModel();

        $newsCategoryModel = new NewsCategoryModel();

        $foreignKey = $newsCategoryModel->select('id')->get()->getResultArray();

        $model->insert([
            'category_id' => static::faker()->randomElement($foreignKey)['id'],
            'name' => static::faker()->name,


        ]);

    }
}