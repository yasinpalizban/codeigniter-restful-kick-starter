<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  NewsMediaEntity extends Entity
{

   protected $id;
   protected $postId;
   protected $image;
   protected $thumbnail;
   protected $video;

    protected $attributes = [
        'id' => null,
        'post_id' => null,
        'thumbnail' => null,
        'video' => null,
        'image' => null,
    ];
    protected $datamap = [
        'postId' =>  'post_id'
    ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];


    public function editPathImage()
    {


        $this->attributes['image'] = 'public/upload/news/image/' . $this->attributes['image'];

        return $this;
    }

    public function editPathThumbNail()
    {


        $this->attributes['thumbnail'] = 'public/upload/news/thumbnail/' . $this->attributes['thumbnail'];

        return $this;
    }

  public function editPathVideo()
    {


        $this->attributes['video'] = 'public/upload/news/video/' . $this->attributes['video'];

        return $this;
    }
}
