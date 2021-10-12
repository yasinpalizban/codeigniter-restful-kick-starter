<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  ContactMediaEntity extends Entity
{

   protected $id;
   protected $contactId;
   protected $path;


    protected $attributes = [
        'id' => null,
        'contact_id' => null,
        'path' => null,

    ];
    protected $datamap = [
        'contactId'=>'contact_id',


    ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];


    public function editPath()
    {


        $this->attributes['path'] = 'public/upload/contact/' . $this->attributes['path'];

        return $this;
    }

}
