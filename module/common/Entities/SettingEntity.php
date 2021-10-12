<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  SettingEntity extends Entity
{


    protected $id;
    protected $key;
    protected $value;
    protected $description;
    protected $status;
    protected $createdAt;
    protected $updatedAt;
    protected $deletedAt;


    protected $attributes = [
        'id' => null,
        'key' => null,
        'value' => null,
        'description' => null,
        'status' => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    protected $datamap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'deletedAt' => 'deleted_at'
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $permissions = [];

    protected $roles = [];

    public function activate()
    {
        $this->attributes['status'] = 1;
        return $this;

    }

    public function deactivate()
    {
        $this->attributes['status'] = 0;

        return $this;
    }

    public function isActivated(): bool
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == true;
    }



//    public function __get(string $key)
//    {
//        if (property_exists($this, $key)) {
//            return $this->$key;
//        }
//    }
//
//    public function __set(string $key, $value = null)
//    {
//        if (property_exists($this, $key)) {
//            $this->$key = $value;
//        }
//    }


//    public function setCreatedAt(string $dateString)
//    {
//        $this->attributes['created_at'] = new Time($dateString, 'UTC');
//
//        return $this;
//    }
//
//    public function getCreatedAt(string $format = 'Y-m-d H:i:s')
//    {
//        // Convert to CodeIgniter\I18n\Time object
//        $this->attributes['created_at'] = $this->mutateDate($this->attributes['created_at']);
//
//        $timezone = $this->timezone ?? app_timezone();
//
//        $this->attributes['created_at']->setTimezone($timezone);
//
//        return $this->attributes['created_at']->format($format);
//    }


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
