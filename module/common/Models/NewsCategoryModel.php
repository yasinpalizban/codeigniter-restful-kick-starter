<?php namespace Modules\Common\Models;

use Modules\Common\Entities\NewsCategoryEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class NewsCategoryModel extends Aggregation
{


    /**
     * table name
     */
    protected $primaryKey = "id";
    protected $table = "news_category";

    /**
     * allowed Field
     */
    protected $allowedFields = [
        'name',
        'language',

    ];




    protected $returnType = NewsCategoryEntity::class;
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'language' => 'required|min_length[2]|max_length[255]',

    ];
    protected $useSoftDeletes = false;
    protected $validationMessages = [];
    protected $skipValidation = false;


}

