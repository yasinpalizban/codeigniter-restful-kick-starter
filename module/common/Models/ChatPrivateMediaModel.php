<?php namespace Modules\Common\Models;


use Modules\Common\Entities\ChatPrivateMediaEntity;

use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class  ChatPrivateMediaModel extends Aggregation
{
    protected $table = 'chat_private_media';
    protected $primaryKey = 'id';

    protected $returnType = ChatPrivateMediaEntity::class;
    protected $allowedFields = [
        'image',
        'chat_private_id',
        'path',


    ];


    protected $validationRules = [

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;


}
