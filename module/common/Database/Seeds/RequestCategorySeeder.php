<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\RequestCategoryModel;

class RequestCategorySeeder extends Seeder
{
    public function run()
    {
        $model = new RequestCategoryModel();


        $model->insert([

            'name' => static::faker()->name,
            'language' => static::faker()->randomElement(['En', 'Fa']),

        ]);
    }
}