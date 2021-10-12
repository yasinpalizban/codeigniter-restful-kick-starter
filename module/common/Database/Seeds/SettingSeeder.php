<?php

namespace Modules\Common\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Modules\Common\Models\SettingModel;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $model = new SettingModel();


        $model->insert([
            'key' => static::faker()->name,
            'value' => static::faker()->phoneNumber,
            'description' => static::faker()->text,
            'status' => static::faker()->boolean,
            'created_at' => static::faker()->date()
        ]);
    }
}