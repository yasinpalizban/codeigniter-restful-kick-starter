<?php namespace Modules\Common\Models;

use Modules\Common\Entities\ChatPrivateEntity;

use Modules\Common\Libraries\CustomFile;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class ChatPrivateModel extends Aggregation
{
    protected $table = 'chat_private';
    protected $primaryKey = 'id';
    protected $returnType = ChatPrivateEntity::class;
    protected $allowedFields = [
        'reply_id',
        'user_sender_id',
        'user_receiver_id',
        'message',
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
        'message' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;


    public function appendChildrenRows(?array $parentRows)
    {
        $chatPrivateMediaModel = new ChatPrivateMediaModel();
        for ($i = 0; $i < count($parentRows); $i++) {


            $parentRows[$i]->media = $chatPrivateMediaModel->where(['chat_private_id' => $parentRows[$i]->id])->orderBy('id')->findAll();

            if ($parentRows[$i]->replyId > 0) {
                $msg = $this->where(['id' => $parentRows[$i]->replyId])->findAll();
                $parentRows[$i]->replyMessage = $msg[0]->message ?? '';
            }
        }

        if (count($parentRows) > 0 && empty($parentRows) &&
            ($parentRows[0]->id > $parentRows[count($parentRows) - 1]->id)) {

            $parentRows = array_reverse($parentRows);
        }
        return $parentRows;
    }


    public function appendMediaRows(int $chatRoomId)
    {
        $chatPrivateMediaModel = new ChatPrivateMediaModel();

        return $chatPrivateMediaModel->where(['chat_private_id' => $chatRoomId])->findAll();
    }

    public function dropLastWeekChats()
    {
        $handy = new CustomFile();

        $curTime = time();
        $today = date("Y-m-d", $curTime);
        $lastWeek = date("Y-m-d", ($curTime - (60 * 60 * 24 * 7)));
        $result = $this->where('created_at<=', $lastWeek)->findAll();
        foreach ($result as $item) {
            $medias = $this->appendMediaRows($item->id);
            foreach ($medias as $me) {
                $handy->removeSingleFile(ROOTPATH . $me->path);
            }
        }
        $this->where('created_at<=', $lastWeek)->delete();

    }

    public function appendLastChat(?array $parentRows, int $userId)
    {

        for ($i = 0; $i < count($parentRows); $i++) {


            $result = $parentRows[$i]->lastChat = $this->select('created_at as date')->where(['user_receiver_id' => $parentRows[$i]->id,
                'user_sender_id' => $userId])->orWhere(['user_sender_id' => $parentRows[$i]->id,
                'user_receiver_id' => $userId])->orderBy('id', 'desc')->limit(1)->asObject()->find();

            $parentRows[$i]->lastChat = $result[0] ?? null;


        }


        return $parentRows;
    }

}
