<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\AdvertisementMediaModel;
use Modules\Common\Models\AdvertisementModel;

class AdvertisementMediaSeeder extends Seeder
{
    public function run()
    {
        $model = new AdvertisementMediaModel();

        $advertisementModel = new AdvertisementModel();
        $foreignKey = $advertisementModel->select('id')->get()->getResultArray();
        $path = static::faker()->image('./upload/advertisement/image', 600, 400);
        $path = "public" . substr($path, 1);
        $path = str_replace('\\', '/', $path);
        $model->insert([
            'advertisement_id' => static::faker()->randomElement($foreignKey)['id'],
            'path' => $path

        ]);
    }
}