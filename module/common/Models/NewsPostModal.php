<?php namespace Modules\Common\Models;

use Modules\Common\Entities\NewsPostEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class NewsPostModal extends Aggregation
{
    protected $table = 'news_post';
    protected $primaryKey = 'id';
    protected $returnType = NewsPostEntity::class;
    protected $allowedFields = [
        'sub_category_id',
        'category_id',
        'picture',
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
        'picture' => 'required|min_length[3]|max_length[255]',
        'body' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function appendChildrenRows(?array $parentRows)
    {
        $flag = false;
        $newsCommentModel = new NewsCommentModel();
        for ($i = 0; $i < count($parentRows); $i++) {
            $parentRows[$i]->commentCount = $newsCommentModel->where(['post_id' => $parentRows[$i]->id])->countAll();
            $flag = true;
        }
        if ($flag == false && count($parentRows)) {
            $parentRows[$i]->commentCount = 0;
        }

        return $parentRows;
    }


}
