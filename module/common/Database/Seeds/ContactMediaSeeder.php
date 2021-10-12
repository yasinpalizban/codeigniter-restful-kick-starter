<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\ContactMediaModal;
use Modules\Common\Models\ContactModal;
use Modules\Common\Models\ViewMediaModel;
use Modules\Common\Models\ViewOptionModel;

class ContactMediaSeeder extends Seeder
{
    public function run()
    {
        $model = new ContactMediaModal();

        $contactModel = new ContactModal();
        $foreignKey = $contactModel->select('id')->get()->getResultArray();
        $path = static::faker()->image('./upload/contact', 600, 400);
        $path = "public" . substr($path, 1);
        $path = str_replace('\\', '/', $path);
        $model->insert([
            'contact_id' => static::faker()->randomElement($foreignKey)['id'],
            'path' => $path

        ]);
    }
}