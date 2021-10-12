<?php namespace Modules\Common\Models;

use Modules\Common\Entities\AdvertisementEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class AdvertisementModel extends Aggregation
{
 //   use \Tatter\Relations\Traits\ModelTrait;
//    protected $with = 'advertisement_media';
    protected $table = 'advertisement';
    protected $primaryKey = 'id';
    protected $returnType = AdvertisementEntity::class;
    protected $allowedFields = [
        'name',
        'link',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'link' => 'required',

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function appendChildrenRows(?array $parentRows)
    {
        $advertisementMediaModel = new AdvertisementMediaModel();

        for ($i = 0; $i < count($parentRows); $i++)
            $parentRows[$i]->media = $advertisementMediaModel->where(['advertisement_id' => $parentRows[$i]->id])->findAll();
        return $parentRows;
    }



}
