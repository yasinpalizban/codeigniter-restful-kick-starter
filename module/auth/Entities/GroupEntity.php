<?php namespace Modules\Auth\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  GroupEntity extends Entity
{

   protected $id;
   protected $name;
   protected $description;


    //check type of data

//    protected $casts = ['
//    is_flag' => 'boolean'];

    protected $attributes = [
        'id' => null,
        'name' => null,
        'description' => null,

    ];
    protected $datamap = [
    ];

    protected $dates = [];

    protected $casts = [];

    protected $permissions = [];

    protected $roles = [];




}
