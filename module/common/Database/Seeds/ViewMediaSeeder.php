<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\ViewMediaModel;
use Modules\Common\Models\ViewOptionModel;

class ViewMediaSeeder extends Seeder
{
    public function run()
    {
        $model = new ViewMediaModel();

        $viewOptionModel = new ViewOptionModel();
        $foreignKey = $viewOptionModel->select('id')->get()->getResultArray();
        $path= static::faker()->image('./upload/view/image', 600, 400);
        $path = "public" . substr($path, 1);
        $path = str_replace('\\', '/', $path);
        $model->insert([
            'view_option_id' => static::faker()->randomElement($foreignKey)['id'],
            'path' =>$path

        ]);
    }
}