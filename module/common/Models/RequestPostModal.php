<?php namespace Modules\Common\Models;


use CodeIgniter\Model;
use Modules\Common\Entities\RequestPostEntity;
use Modules\Shared\Models\Aggregation;

class RequestPostModal extends Aggregation
{
    protected $table = 'request_post';
    protected $primaryKey = 'id';
    protected $returnType = RequestPostEntity::class;
    protected $allowedFields = [

        'category_id',
        'user_id',
        'title',
        'body',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'body' => 'required',

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function appendChildrenRows(?array $parentRows)
    {
        $requestReplyModel = new RequestReplyModel();
        $flag = false;
        for ($i = 0; $i < count($parentRows); $i++) {
            $parentRows[$i]->replyCount = $requestReplyModel->where(['post_id' => $parentRows[$i]->id])->countAll();
            $flag = true;
        }
        if ($flag == false  && count($parentRows)) {
            $parentRows[$i]->replyCount = 0;
        }
        return $parentRows;
    }


}
