<?php namespace Modules\Common\Models;

use Modules\Common\Entities\ContactMediaEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class ContactMediaModal extends Aggregation
{
    protected $table = 'contact_media';
    protected $primaryKey = 'id';

    protected $returnType = ContactMediaEntity::class;
    protected $allowedFields = [
        'path',
        'contact_id'


    ];


    protected $validationRules = [
        'path' => 'required',


    ];
    protected $validationMessages = [];
    protected $skipValidation = false;



}
