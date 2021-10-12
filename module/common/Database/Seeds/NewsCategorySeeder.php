<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\NewsCategoryModel;

class NewsCategorySeeder extends Seeder
{
    public function run()
    {
        $model = new NewsCategoryModel();


        $model->insert([

            'name' => static::faker()->name,
            'language' => static::faker()->randomElement(['En', 'Fa']),

        ]);
    }
}