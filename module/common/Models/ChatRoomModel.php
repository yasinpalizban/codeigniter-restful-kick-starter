<?php namespace Modules\Common\Models;

use Modules\Common\Entities\ChatRoomEntity;
use Modules\Common\Libraries\CustomFile;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;
use function PHPUnit\Framework\isEmpty;

class ChatRoomModel extends Aggregation
{
    protected $table = 'chat_room';
    protected $primaryKey = 'id';
    protected $returnType = ChatRoomEntity::class;
    protected $allowedFields = [
        'group_id',
        'reply_id',
        'user_id',
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
        $chatRoomMediaModel = new ChatRoomMediaModel();

        for ($i = 0; $i < count($parentRows); $i++) {

            if ($parentRows[$i]->replyId > 0) {

                $msg = $this->where(['id' => $parentRows[$i]->replyId])->findAll();

                $parentRows[$i]->replyMessage = $msg[0]->message ?? '';
            }
            $parentRows[$i]->media = $chatRoomMediaModel->where(['chat_room_id' => $parentRows[$i]->id])->findAll();

        }

        if ( !empty($parentRows) &&
            ($parentRows[0]->id > $parentRows[count($parentRows)-1]->id)) {

            $parentRows=  array_reverse($parentRows);
        }


        return $parentRows;
    }

    public function appendMediaRows(int $chatRoomId)
    {
        $chatRoomMediaModel = new ChatRoomMediaModel();

        $media = $chatRoomMediaModel->where(['chat_room_id' => $chatRoomId])->findAll();


        return $media;


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

    public function appendLastChat(?array $parentRows, int $groupId)
    {

        for ($i = 0; $i < count($parentRows); $i++) {
            $result = $this->select('created_at  as date')->where(['group_id' => $groupId])->orderBy('id', 'desc')->limit(1)->asObject()->find();

            $parentRows[$i]->lastChat = $result[0] ?? null;
        }


        return $parentRows;
    }
}
