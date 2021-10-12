<?php namespace Modules\Auth\Entities;

use \CodeIgniter\Entity;

class  GroupsPermissionEntity extends Entity
{

   protected $groupId;
   protected $permissionId;
   protected $description;


    //check type of data

//    protected $casts = ['
//    is_flag' => 'boolean'];

    protected $attributes = [
        'group_id' => null,
        'permission_id' => null,
        'actions' => null,

    ];
    protected $datamap = [
        'groupId'=>'group_id' ,
        'permissionId'=>'permission_id' ,
    ];

    protected $dates = [];

    protected $casts = [];

    protected $permissions = [];

    protected $roles = [];




}
