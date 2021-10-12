<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  ChatRoomEntity extends Entity
{


   protected $id;
   protected $userId;
   protected $groupId;
   protected $replyId;
   protected $message;
   protected $status;
   protected $createdAt;
   protected $updatedAt;
   protected $deletedAt;


    protected $attributes = [
        'id' => null,
        'group_id' => null,
        'user_id' => null,
        'reply_id' => 0,
        'message' => null,
        'status' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    protected $datamap = [
        'groupId' => 'group_id',
        'userId' =>  'user_id',
        'replyId' => 'reply_id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at' ,
        'deletedAt' => 'deleted_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $permissions = [];

    protected $roles = [];

    public function disableStatus()
    {
        $this->attributes['status'] = 0;
        return $this;

    }

    public function enableStatus()
    {
        $this->attributes['status'] = 1;

        return $this;
    }

    public function isStatus(): bool
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == true;
    }

    public function createdAt()
    {

        date_default_timezone_set('Asia/Tehran');
        $this->attributes['created_at'] = date('Y-m-d H:i:s', time());
        // $this->attributes['created_at'] = new  Time(date('Y-m-d H:i:s', time()), 'UTC');
        return $this;
    }

    public function updatedAt()
    {

        date_default_timezone_set('Asia/Tehran');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s', time());

        return $this;
    }

}
