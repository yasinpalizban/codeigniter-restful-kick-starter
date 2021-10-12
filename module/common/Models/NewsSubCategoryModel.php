<?php namespace Modules\Common\Models;

use Modules\Common\Entities\NewsCategoryEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class NewsSubCategoryModel extends Aggregation
{


    /**
     * table name
     */
    protected $primaryKey = "id";
    protected $table = "news_sub_category";

    /**
     * allowed Field
     */
    protected $allowedFields = [
        'category_id',
        'name',


    ];


    protected $returnType = NewsCategoryEntity::class;
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'category_id' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $useSoftDeletes = false;


}

