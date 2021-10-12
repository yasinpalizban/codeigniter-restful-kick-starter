<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  VisitorEntity extends Entity
{

   protected $id;
   protected $ip;
   protected $country;
   protected $city;
   protected $os;
   protected $lat;
   protected $lang;
   protected $createdAt;
   protected $updatedAt;
   protected $deletedAt;



    protected $attributes = [
        'id' => null,
        'ip' => null,
        'country' => null,
        'city' => null,
        'os' => null,
        'lat' => null,
        'lang' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,

    ];
    protected $datamap = [
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

        // date_default_timezone_set('Asia/Tehran');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s', time());
        // $this->attributes['created_at'] = new  Time(date('Y-m-d H:i:s', time()), 'UTC');
        return $this;
    }
}
