<?php namespace Modules\Common\Models;

use Modules\Common\Entities\NewsMediaEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class NewsMediaModel extends Aggregation
{
    protected $table = 'news_media';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = false;
    protected $returnType = NewsMediaEntity::class;
    protected $allowedFields = [
        'image',
        'post_id',
        'thumbnail',
        'video'

    ];


    protected $validationRules = [

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;


}
