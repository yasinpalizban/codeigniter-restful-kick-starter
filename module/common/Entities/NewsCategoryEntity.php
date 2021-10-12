<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class NewsCategoryEntity extends Entity
{

   protected $id;
   protected $name;
   protected $categroyId;


    //check type of data

//    protected $casts = ['
//    is_flag' => 'boolean'];

    protected $attributes = [
        'id' => null,
        'name' => null,
        'category_id' => null,

    ];
    protected $datamap = [
        'categoryId' => 'category_id'
        ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];

}
