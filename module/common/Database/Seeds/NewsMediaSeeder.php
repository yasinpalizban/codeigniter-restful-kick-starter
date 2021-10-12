<?php namespace Modules\Common\Database\Seeds;


use CodeIgniter\Database\Seeder;
use Modules\Common\Models\NewsMediaModel;
use Modules\Common\Models\NewsPostModal;

class NewsMediaSeeder extends Seeder
{
    public function run()
    {
        $model = new NewsMediaModel();

        $newsPostModel = new NewsPostModal();
        $foreignKey = $newsPostModel->select('id')->get()->getResultArray();
        $path = static::faker()->image('./upload/news/thumbnail', 200, 100);
        $path = "public" . substr($path, 1);
        $path = str_replace('\\', '/', $path);

        $path2 = static::faker()->image('./upload/news/image', 600, 400);
        $path2 = "public" . substr($path2, 1);
        $path2 = str_replace('\\', '/', $path2);
        $model->insert([
            'post_id' => static::faker()->randomElement($foreignKey)['id'],
            'thumbnail' => $path,
            'image' => $path2,



        ]);
    }
}