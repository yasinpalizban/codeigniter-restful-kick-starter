<?php namespace Modules\Common\Models;

use Modules\Common\Entities\VisitorEntity;
use CodeIgniter\Model;
use Modules\Shared\Models\Aggregation;

class  VisitorModel extends Aggregation
{


    /**
     * table name
     */
    protected $primaryKey = "id";
    protected $table = "visitor";

    /**
     * allowed Field
     */
    protected $allowedFields = [
        'ip',
        'country',
        'city',
        'os',
        'lat',
        'lang',
        'created_at',
        'updated_at',
        'deleted_at',

    ];
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    //protected $returnType = "App\Entities\SettingEntity";
    protected $returnType = VisitorEntity::class;
    protected $validationRules = [
        'ip' => 'required|max_length[255]',
        'country' => 'required|max_length[255]',
        'city' => 'required|max_length[255]',
        'os' => 'required|max_length[255]',
        'lat' => 'required|max_length[255]',
        'lang' => 'required|max_length[255]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function keepLimitedIp(int $limit)
    {


        $lastId = $this->orderBy('id', 'DESC')->limit(1)->first();

        $firstId = $this->orderBy('id', 'ASC')->limit(1)->first();

        if (is_object($firstId)) {
            $lastId = $lastId->id;
            $firstId = $firstId->id;

        }

        $rows = $this->countAllResults();
        if ($limit >= $rows) {
            //do nothing
        } else {


            $index = (($lastId - $firstId) - $limit);

            for ($i = 0; $i < $index; $i++) {

                $id = ($i + $firstId);
                $this->delete($id);
            }

        }


    }
}

