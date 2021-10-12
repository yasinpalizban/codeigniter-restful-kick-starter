<?php namespace Modules\Common\Entities;

use \CodeIgniter\Entity;
use CodeIgniter\I18n\Time;

class ViewsMediaEntity extends Entity
{

   protected $id;
   protected $viewOptionId;
   protected $path;


    protected $attributes = [
        'id' => null,
        'view_option_id' => null,
        'path' => null,

    ];
    protected $datamap = [
        'viewOptionId' => 'view_option_id',
    ];

    protected $dates = [];

    protected $casts = [

    ];

    protected $permissions = [];

    protected $roles = [];


    public function editPath(?bool $flag)
    {
        $append = ($flag == false ?  'video/':'image/' );
        $this->attributes['path'] = 'public/upload/view/' . $append . $this->attributes['path'];

        return $this;
    }

}
