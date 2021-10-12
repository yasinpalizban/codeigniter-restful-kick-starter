<?php

namespace Modules\Shared\Models;

use CodeIgniter\Model;
use Config\Services;
use Exception;
use Modules\Shared\Interfaces\AggregationInterface;

class Aggregation extends Model implements AggregationInterface
{


    public function aggregate(array $pipeLine): array
    {

        if (empty($pipeLine)) {
            throw  new Exception(' aggregate pile line can not be empty');
        }

        $builder = $this->db->table($this->table);
        if (isset($pipeLine['select'])) {
            $builder->select($pipeLine['select']);
        }


        if (isset($pipeLine['join'])) {
            foreach ($pipeLine['join'] as $item) {
                $builder->join($item['table'], $item['condition'], $item['mode']);
            }

        }

        if (isset($pipeLine['whereIn'])) {
            $builder->whereIn($pipeLine['whereIn']['key'], $pipeLine['whereIn']['value']);
        }
        if (isset($pipeLine['whereNotIn'])) {
            $builder->whereNotIn($pipeLine['whereNotIn']['key'], $pipeLine['whereNotIn']['value']);
        }
        if (isset($pipeLine['orWhereIn'])) {
            $builder->orWhereIn($pipeLine['orWhereIn']['key'], $pipeLine['orWhereIn']['value']);
        }
        if (isset($pipeLine['orWhereNotIn'])) {
            $builder->orWhereNotIn($pipeLine['orWhereNotIn']['key'], $pipeLine['orWhereNotIn']['value']);
        }
        if (isset($pipeLine['where'])) {
            $builder->where($pipeLine['where']);
        }
        if (isset($pipeLine['orWhere'])) {
            $builder->orWhere($pipeLine['orWhere']);
        }
        if (isset($pipeLine['like'])) {
            $builder->like($pipeLine['like']);
        }
        if (isset($pipeLine['orLike'])) {
            $builder->orLike($pipeLine['orLike']);
        }
        if (isset($pipeLine['orNotLike'])) {
            $builder->orNotLike($pipeLine['orNotLike']);
        }
        if (isset($pipeLine['groupBy'])) {
            $builder->groupBy($pipeLine['groupBy']);
        }
        if (isset($pipeLine['having'])) {
            $builder->having($pipeLine['having']);
        }
        if (isset($pipeLine['orHaving'])) {
            $builder->orHaving($pipeLine['orHaving']);
        }

        if (isset($pipeLine['orHavingIn'])) {
            $builder->orHavingIn($pipeLine['orHavingIn']['key'], $pipeLine['orHavingIn']['value']);
        }
        if (isset($pipeLine['havingNotIn'])) {
            $builder->havingNotIn($pipeLine['orHavingIn']['key'], $pipeLine['orHavingIn']['value']);
        }
        if (isset($pipeLine['havingLike'])) {
            $builder->havingLike($pipeLine['havingLike']);
        }


        if (isset($pipeLine['sort']) && isset($pipeLine['order'])) {
            $builder->orderBy($pipeLine['sort'], $pipeLine['order']);
        }

        if (isset($pipeLine['limit'])) {
            $builder->limit($pipeLine['limit']);
        }
        if (isset($pipeLine['offset'])) {
            $builder->offset($pipeLine['offset']);
        }

        return $builder->get()->getCustomResultObject($this->returnType);

    }

    public function aggregatePagination(array $pipeLine): array
    {

        if (empty($pipeLine)) {
            throw  new Exception(' aggregate pile line can not be empty');
        }
        $builder = $this->db->table($this->table);

        $builder->select($pipeLine['select']);

        if (isset($pipeLine['join'])) {
            foreach ($pipeLine['join'] as $item) {
                $builder->join($item['table'], $item['condition'], $item['mode']);
            }

        }


        if (isset($pipeLine['whereIn'])) {
            $builder->whereIn($pipeLine['whereIn']['key'], $pipeLine['whereIn']['value']);
        }
        if (isset($pipeLine['whereNotIn'])) {
            $builder->whereNotIn($pipeLine['whereNotIn']['key'], $pipeLine['whereNotIn']['value']);
        }
        if (isset($pipeLine['orWhereIn'])) {
            $builder->orWhereIn($pipeLine['orWhereIn']['key'], $pipeLine['orWhereIn']['value']);
        }
        if (isset($pipeLine['orWhereNotIn'])) {
            $builder->orWhereNotIn($pipeLine['orWhereNotIn']['key'], $pipeLine['orWhereNotIn']['value']);
        }
        if (isset($pipeLine['where'])) {
            $builder->where($pipeLine['where']);
        }
        if (isset($pipeLine['orWhere'])) {
            $builder->orWhere($pipeLine['orWhere']);
        }
        if (isset($pipeLine['like'])) {
            $builder->like($pipeLine['like']);
        }
        if (isset($pipeLine['orLike'])) {
            $builder->orLike($pipeLine['orLike']);
        }
        if (isset($pipeLine['orNotLike'])) {
            $builder->orNotLike($pipeLine['orNotLike']);
        }
        if (isset($pipeLine['groupBy'])) {
            $builder->groupBy($pipeLine['groupBy']);
        }
        if (isset($pipeLine['having'])) {
            $builder->having($pipeLine['having']);
        }
        if (isset($pipeLine['orHaving'])) {
            $builder->orHaving($pipeLine['orHaving']);
        }

        if (isset($pipeLine['orHavingIn'])) {
            $builder->orHavingIn($pipeLine['orHavingIn']['key'], $pipeLine['orHavingIn']['value']);
        }
        if (isset($pipeLine['havingNotIn'])) {
            $builder->havingNotIn($pipeLine['orHavingIn']['key'], $pipeLine['orHavingIn']['value']);
        }
        if (isset($pipeLine['havingLike'])) {
            $builder->havingLike($pipeLine['havingLike']);
        }

        if (isset($pipeLine['sort']) && isset($pipeLine['order'])) {
            $builder->orderBy($pipeLine['sort'], $pipeLine['order']);
        }
        if (isset($pipeLine['limit'])) {
            $builder->limit($pipeLine['limit']);
        }
        if (isset($pipeLine['page'])) {
            $offSet = ($pipeLine['page'] - 1) * $pipeLine['limit'];
            $builder->offset($offSet);
        }
        $pagination = Services::pager(null, null, false);

        if (isset($pipeLine['offset']) && isset($pipeLine['limit']) && isset($pipeLine['page'])) {
            $pagination->setSegment($pipeLine['offset']);
            $paging = $pipeLine['page'] >= 1 ? $pipeLine['page'] : $pagination->getCurrentPage('default');
            $this->pager = $pagination->store('default', $paging, $pipeLine['limit'], $builder->countAllResults(false), $pipeLine['offset']);
        }

        return ['data' =>
            $builder->get()->getCustomResultObject($this->returnType),
            'pager' => $pagination->getDetails()

        ];

    }


}