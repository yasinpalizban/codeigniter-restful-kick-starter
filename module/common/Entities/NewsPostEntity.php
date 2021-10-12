<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  NewsPostEntity extends Entity
{



    public $id;
   protected $subCategoryId;
   protected $categoryId;
   protected $userId;

   protected $picture;
   protected $title;
   protected $body;


   protected $status;
   protected $createdAt;
   protected $updatedAt;
   protected $deletedAt;


    protected $attributes = [
        'id' => null,
        'sub_category_id' => null,
        'category_id' => null,
        'user_id' => null,
        'title' => null,
        'body' => null,
        'status' => null,
        'picture' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    protected $datamap = [
        'subCategoryId' =>'sub_category_id',
        'categoryId' => 'category_id',
        'userId' => 'user_id',
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

        //    date_default_timezone_set('Asia/Tehran');
        $this->attributes['created_at'] = date('Y-m-d H:i:s', time());
        // $this->attributes['created_at'] = new  Time(date('Y-m-d H:i:s', time()), 'UTC');
        return $this;
    }

    public function updatedAt()
    {

        // date_default_timezone_set('Asia/Tehran');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s', time());

        return $this;
    }


    public function initPicture()
    {
        $this->attributes['picture'] = 'copy image link here!';
        return $this;

    }


    public function editPicture()
    {


        $this->attributes['picture'] = 'public/upload/news/thumbnail' . $this->attributes['picture'];

        return $this;
    }

}
