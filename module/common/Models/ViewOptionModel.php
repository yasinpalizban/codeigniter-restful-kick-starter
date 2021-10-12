<?php namespace Modules\Common\Models;

use Modules\Common\Entities\ViewsOptionEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class ViewOptionModel extends Aggregation
{
    protected $table = 'view_option';
    protected $primaryKey = 'id';

    protected $returnType = ViewsOptionEntity::class;
    protected $allowedFields = [
        'name',

    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $validationRules = [
        'name' => 'required|max_length[255]',

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function appendChildrenRows(?array $parentRows)
    {
       $viewMediaModal= new ViewMediaModel();
        for ($i = 0; $i < count($parentRows); $i++)
            $parentRows[$i]->media =   $viewMediaModal->where(['view_option_id' => $parentRows[$i]->id])->findAll();
        return $parentRows;
    }

}
