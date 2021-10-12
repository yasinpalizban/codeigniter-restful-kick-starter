<?php namespace Modules\Auth\Entities;

use \CodeIgniter\Entity;

class  PermissionEntity extends Entity
{

   protected $id;
   protected $name;
   protected $description;
   protected $active;


    //check type of data

//    protected $casts = ['
//    is_flag' => 'boolean'];

    protected $attributes = [
        'id' => null,
        'name' => null,
        'description' => null,
        'active' => null,

    ];
    protected $datamap = [
    ];

    protected $dates = [];

    protected $casts = [];

    protected $permissions = [];

    protected $roles = [];




}
