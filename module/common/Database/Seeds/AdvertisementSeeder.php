<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\AdvertisementModel;

class AdvertisementSeeder extends Seeder
{
    public function run()
    {
        $model = new AdvertisementModel();


        $model->insert([

            'link' => static::faker()->url,
            'name' => static::faker()->name,
            'status' => static::faker()->boolean,
            'created_at' => static::faker()->date()

        ]);
    }
}