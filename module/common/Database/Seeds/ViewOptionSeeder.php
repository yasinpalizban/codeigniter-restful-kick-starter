<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\ViewOptionModel;

class ViewOptionSeeder extends Seeder
{
    public function run()
    {
        $model = new ViewOptionModel();


        $model->insert([

            'name' => static::faker()->name,

        ]);
    }
}