<?php namespace Modules\Common\Models;

use Modules\Common\Entities\NewsCommentEntity;
;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class NewsCommentModel extends Aggregation
{
    protected $table = 'news_comment';
    protected $primaryKey = 'id';
    protected $returnType = NewsCommentEntity::class;
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


}
