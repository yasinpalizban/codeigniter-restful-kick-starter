<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class NewsSubCategoryEntity extends Entity
{

   protected $id;
   protected $categoryId;
   protected $name;


    //check type of data

//    protected $casts = ['
//    is_flag' => 'boolean'];

    protected $attributes = [
        'id' => null,
        'category_id' => null,
        'name' => null,


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
