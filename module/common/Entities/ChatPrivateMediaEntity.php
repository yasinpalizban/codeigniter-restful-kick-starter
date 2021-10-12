<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class ChatPrivateMediaEntity extends Entity
{

    protected $id;
    protected $chatPrivateId;
    protected $path;


    protected $attributes = [
        'id' => null,
        'chat_private_id' => null,
        'path' => null,

    ];
    protected $datamap = [
        'chatPrivateId'=>'chat_private_id'
    ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];


    public function editPath()
    {


        $this->attributes['path'] = 'public/upload/chat_private/' . $this->attributes['path'];

        return $this;
    }


}
