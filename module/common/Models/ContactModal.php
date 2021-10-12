<?php namespace Modules\Common\Models;

use Modules\Common\Entities\ContactEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class ContactModal extends Aggregation
{
    protected $table = 'contact';
    protected $primaryKey = 'id';

    protected $returnType = ContactEntity::class;
    protected $allowedFields = [
        'title',
        'email',
        'message',
        'reply',
        'phone',
        'status',
        'name',
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
        'title' => 'if_exist|required|max_length[255]',
        'email' => 'if_exist|required|valid_email',
        'message' => 'if_exist|required',
        'reply' =>  'if_exist',
        'phone' => 'if_exist|required|numeric'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function appendChildrenRows(?array $parentRows)
    {

        $contactMediaModal = new ContactMediaModal();

        for ($i = 0; $i < count($parentRows); $i++)
            $parentRows[$i]->media = $contactMediaModal->where(['contact_id' => $parentRows[$i]->id])->findAll();
        return $parentRows;
    }


}
