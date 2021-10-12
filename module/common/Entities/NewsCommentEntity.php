<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  NewsCommentEntity extends Entity
{


   protected $id;
   protected $postId;
   protected $userId;
   protected $replyId;
   protected $message;
   protected $createdAt;
   protected $updatedAt;
   protected $deletedAt;



    protected $attributes = [
        'id' => null,
        'post_id' => null,
        'user_id' => null,
        'reply_id' => null,
        'message' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    protected $datamap = [
        'postId' => 'post_id',
        'userId' => 'user_id',
        'replyId' => 'reply_id',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at' ,
        'deletedAt' => 'deleted_at'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];



    public function createdAt()
    {

       // date_default_timezone_set('Asia/Tehran');
        $this->attributes['created_at'] = date('Y-m-d H:i:s', time());
        // $this->attributes['created_at'] = new  Time(date('Y-m-d H:i:s', time()), 'UTC');
        return $this;
    }

    public function updatedAt()
    {

      //  date_default_timezone_set('Asia/Tehran');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s', time());

        return $this;
    }

}
