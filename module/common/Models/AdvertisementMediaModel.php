<?php namespace Modules\Common\Models;

use Modules\Common\Entities\AdvertisementMediaEntity;

use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class AdvertisementMediaModel extends Aggregation
{
    protected $table = 'advertisement_media';
    protected $primaryKey = 'id';

    protected $returnType = AdvertisementMediaEntity::class;
    protected $allowedFields = [
        'image',
        'advertisement_id',
        'path',


    ];


    protected $validationRules = [

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;


}
