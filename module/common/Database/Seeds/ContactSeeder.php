<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\ContactModal;
class ContactSeeder extends Seeder
{
    public function run()
    {
        $model = new ContactModal();


        $model->insert([

            'title' => static::faker()->title,
            'email' => static::faker()->email,
            'name' => static::faker()->name,
            'message' => static::faker()->text,
            'phone' => static::faker()->biasedNumberBetween(),
            'status' => static::faker()->boolean,
            'created_at' => static::faker()->date()

        ]);


    }
}