<?php namespace Modules\Common\Models;

use Modules\Common\Entities\SettingEntity;
use Modules\Shared\Models\Aggregation;

class  SettingModel extends Aggregation
{


    /**
     * table name
     */
    protected $primaryKey = "id";
    protected $table = "setting";

    /**
     * allowed Field
     */
    protected $allowedFields = [
        'key',
        'value',
        'description',
        'status',
        'deleted_at',
        'updated_at',
        'created_at'
    ];
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = SettingEntity::class;
    protected $validationRules = [
        'key' => 'required|min_length[3]|max_length[255]|is_unique[setting.key]',
        'value' => 'required|min_length[3]|max_length[255]',
        'description' => 'required|min_length[3]|max_length[255]',
        'status' => 'required'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;


}

