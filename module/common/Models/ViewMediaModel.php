<?php namespace Modules\Common\Models;


use Modules\Common\Entities\ViewsMediaEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class ViewMediaModel extends Aggregation
{
    protected $table = 'view_media';
    protected $primaryKey = 'id';

    protected $returnType =ViewsMediaEntity::class;
    protected $allowedFields = [
        'path',
        'view_option_id'


    ];


    protected $validationRules = [
        'path' => 'required',


    ];
    protected $validationMessages = [];
    protected $skipValidation = false;



}
