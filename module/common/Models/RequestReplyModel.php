<?php namespace Modules\Common\Models;


use CodeIgniter\Model;
use Modules\Common\Entities\RequestReplyEntity;
use Modules\Shared\Models\Aggregation;

class RequestReplyModel extends Aggregation
{
    protected $table = 'request_reply';
    protected $primaryKey = 'id';
    protected $returnType = RequestReplyEntity::class;
    protected $allowedFields = [
        'post_id',
        'user_id',
        'reply_id',
        'message',
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
        'message' => 'if_exist|required',
        'post_id' => 'required',
        'user_id' => 'required',
        'reply_id' => 'required',

    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function appendReplyRows(?array $parentRows)
    {

        for ($i = 0; $i < count($parentRows); $i++) {

            if ($parentRows[$i]->replyId > 0) {
                $msg = $this->where(['id' => $parentRows[$i]->reply_id])->findAll();
                $parentRows[$i]->replyMessage = isset($msg[0]->message) ? $msg[0]->message : '';
            }

        }

//        if (empty($parentRows) &&
//            ($parentRows[0]->id > $parentRows[count($parentRows)]->id)) {
//            array_reverse($parentRows);
//        }
        return $parentRows;
    }

}
