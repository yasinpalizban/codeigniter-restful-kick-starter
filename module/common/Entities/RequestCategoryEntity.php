<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class RequestCategoryEntity extends Entity
{

   protected $id;
   protected $name;
   protected $language;


    //check type of data

//    protected $casts = ['
//    is_flag' => 'boolean'];

    protected $attributes = [
        'id' => null,
        'name' => null,
        'language' => null,

    ];
    protected $datamap = [
    ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];

}
