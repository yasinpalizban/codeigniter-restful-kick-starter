<?php namespace Modules\Common\Models;


use Modules\Common\Entities\ChatRoomMediaEntity;

use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class ChatRoomMediaModel extends Aggregation
{
    protected $table = 'chat_room_media';
    protected $primaryKey = 'id';

    protected $returnType = ChatRoomMediaEntity::class;
    protected $allowedFields = [
        'image',
        'chat_room_id',
        'path',


    ];


    protected $validationRules = [

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;


}
