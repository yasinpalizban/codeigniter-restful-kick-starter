<?php namespace Modules\Common\Models;


use CodeIgniter\Model;
use Modules\Common\Entities\RequestCategoryEntity;
use Modules\Shared\Models\Aggregation;

class RequestCategoryModel extends Aggregation
{


    /**
     * table name
     */
    protected $primaryKey = "id";
    protected $table = "request_category";

    /**
     * allowed Field
     */
    protected $allowedFields = [
        'name',
        'language',

    ];




    protected $returnType = RequestCategoryEntity::class;
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'language' => 'required|min_length[2]|max_length[255]',

    ];
    protected $useSoftDeletes = false;
    protected $validationMessages = [];
    protected $skipValidation = false;


}

