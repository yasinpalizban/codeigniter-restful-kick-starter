<?php namespace Modules\Auth\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class  UsersPermissionEntity extends Entity
{

   protected $userId;
   protected $permissionId;
   protected $description;


    //check type of data

//    protected $casts = ['
//    is_flag' => 'boolean'];

    protected $attributes = [
        'user_id' => null,
        'permission_id' => null,
        'actions' => null,

    ];
    protected $datamap = [
        'userId'=>'user_id' ,
        'permissionId'=>'permission_id' ,
    ];

    protected $dates = [];

    protected $casts = [];

    protected $permissions = [];

    protected $roles = [];




}
