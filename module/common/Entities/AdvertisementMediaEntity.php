<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  AdvertisementMediaEntity extends Entity
{

    protected $id;
    protected $advertisementId;
    protected $path;


    protected $attributes = [
        'id' => null,
        'advertisement_id' => null,
        'path' => null,

    ];
    protected $datamap = [
        'advertisementId' => 'advertisement_id'
    ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];


    public function editPath(?bool $flag)
    {
        $append = ($flag == false ? 'video/' : 'image/');

        $this->attributes['path'] = 'public/upload/advertisement/' . $append . $this->attributes['path'];

        return $this;
    }


}
