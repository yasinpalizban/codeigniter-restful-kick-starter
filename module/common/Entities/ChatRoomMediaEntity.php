<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class ChatRoomMediaEntity extends Entity
{

   protected $id;
   protected $chatRoomId;
   protected $path;


    protected $attributes = [
        'id' => null,
        'chat_room_id' => null,
        'path' => null,

    ];
    protected $datamap = [
        'chatRoomId' => 'chat_room_id',
    ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];


    public function editPath(string $path)
    {


        $this->attributes['path'] = 'public/upload/chat_room/' . $path;

        return $this;
    }


}
