<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\VisitorModel;

class VisitorSeeder extends Seeder
{
    public function run()
    {
        $model = new VisitorModel();


        $model->insert([
            'ip' => static::faker()->ipv4,
            'country' => static::faker()->country,
            'city' => static::faker()->city,
            'os' => static::faker()->userAgent,
            'lat' => static::faker()->latitude,
            'lang' => static::faker()->longitude,
            'created_at' => static::faker()->date()
        ]);
    }
}