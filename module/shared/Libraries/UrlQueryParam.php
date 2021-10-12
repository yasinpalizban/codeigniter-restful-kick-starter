<?php namespace Modules\Shared\Libraries;


use CodeIgniter\HTTP\RequestInterface;
use Modules\Shared\Interfaces\UrlQueryParamInterface;


class  UrlQueryParam implements UrlQueryParamInterface
{

    private const  Point = ".";
    private int $limit;
    private int $offset;
    private string $range;
    private int $page;
    private string $filed;
    private array $q;
    private string $order;
    private string $sort;
    private int $foreignKey;
    private string $tableName;
    private array $pipeLine;


    public function __construct(RequestInterface $request)
    {

        $this->range = $request->getGet('range') ?? '1to10';
        $this->sort = $request->getGet('sort') ?? 'id';
        $this->order = $request->getGet('order') ?? 'desc';
        $this->page = $request->getGet('page') ?? 1;
        $this->limit = $request->getGet('limit') ?? '10';
        $this->offset = $request->getGet('offset') ?? '0';
        $this->filed = $request->getGet('filed') ?? '';
        $this->foreignKey = $request->getGet('foreignKey') ?? 0;
        $this->q = [];
        isset($_GET['q']) ? parse_str($request->getGet('q'), $this->q) : $this->q = [];
        $this->tableName = '';
        $this->pipeLine = [];


    }


    /**
     * @param array $dataMap
     */
    public function dataMap(array $dataMap): void
    {

        $object = [];
        $isEqual = false;
        foreach ($this->q as $key => $value) {

            foreach ($dataMap as $needle => $hook) {
                if ($needle == $key) {

                    $object[$hook] = $value;
                    $isEqual = true;
                }
            }
            if ($isEqual == true) {
                $isEqual = false;
            } else {
                $object[$key] = $value;
            }

        }
        $this->q = $object;
    }


    /**
     * @return array
     */
    public function decodeQueryParam(): UrlQueryParam
    {
        $whiteList = ['whereIn',
            'whereNotIn',
            'orWhereIn',
            'orWhereNotIn',
            'orHavingIn',
            'havingNotIn'];

        $temp = [];
        $object = null;
        $counter = 0;
        foreach ($this->q as $key => $value) {
            $object = json_decode($value);

            if (in_array($object->fun, $whiteList)) {
                if (isset($object->jin)) {
                    $temp['key'] = $object->jin . self::Point . $key;
                } else {
                    $temp['key'] = $this->tableName . $key;

                }
                $temp['value'] = $object->val;
            } else if (isset($object->sgn) && !empty($object->sgn) && is_array($object->sgn)) {

                foreach ($object->sgn as $sgn) {
                    $key = $key . ' ' . $sgn;

                    if (isset($object->jin)) {
                        $temp[$object->jin . self::Point . $key] = $object->val[$counter];
                    } else {
                        $temp[$this->tableName . $key] = $object->val[$counter];
                    }
                    $counter++;
                }
            } else if (isset($object->sgn) && !empty($object->sgn) && is_string($object->sgn)) {

                $key = $key . ' ' . $object->sgn;

                if (isset($object->jin)) {
                    $temp[$object->jin . self::Point . $key] = $object->val;
                } else {
                    $temp[$this->tableName . $key] = $object->val;
                }
            } else {

                if (isset($object->jin)) {
                    $temp[$object->jin . self::Point . $key] = $object->val;
                } else {
                    $temp[$this->tableName . $key] = $object->val;
                }
            }
            $counter = 0;
            $this->pipeLine[str_replace(' ', '', $object->fun)] = $temp;
        }
        return $this;
    }


    private
    function selectFields(string $query): string
    {
        if ($this->filed != '') {
            return $this->filed;
        } else {
            return $query;
        }

    }

    /**
     * @return int
     */
    public
    function getForeignKey(): int
    {
        return $this->foreignKey;
    }


    /**
     * @param string $append
     */
    public
    function setTableName(string $append): UrlQueryParam
    {
        $this->tableName = $append . self::Point;
        $this->tableName = $str = str_replace(' ', '', $this->tableName);
        return $this;
    }

    public
    function encodeQueryParam(string $key, string $value, string $function, string $sign, string $joinWith = ''): string
    {


        $data = ['fun' => $function, 'val' => $value, 'sgn' => $sign];
        if (strlen($joinWith) > 0)
            $data['jin'] = $joinWith;

        return http_build_query(array($key => [json_encode($data)]));


    }

    public
    function getPipeLine(?array $defaultPipeLine = null): array
    {
        $this->pipeLine['offset'] = $this->offset;
        $this->pipeLine['limit'] = $this->limit;
        $this->pipeLine['page'] = $this->page;
        $this->pipeLine['order'] = $this->order;
        $this->pipeLine['sort'] = strlen($this->tableName) ? $this->tableName . $this->sort : $this->sort;


        $this->pipeLine['select'] = (isset($defaultPipeLine['select'])) ? $this->selectFields($defaultPipeLine['select']) : $this->tableName . '*';
        if ((isset($defaultPipeLine['join']))) {
            $this->pipeLine['join'] = $defaultPipeLine['join'];

        }
        if (!empty($defaultPipeLine)) {
            foreach ($defaultPipeLine as $key => $value) {
                if ($key != 'select' && $key != 'join' && array_key_exists($key, $this->pipeLine)) {

                    $this->pipeLine[$key] = array_merge($this->pipeLine[$key], $value);
                }
            }
        }




        return $this->pipeLine;
    }


}
