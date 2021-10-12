<?php
function serializeMessages($data): string
{

    $line = " ";
    foreach ($data as $item) {
        $line .= $item;
        $line .= "\n ";

    }

    return $line;
}

function compareDates($first, $second): bool
{


    if (isset($first) and count($first[0]) > 0) {
        $first = strtotime($first[0]['start_date']);
        $second = strtotime($second[0]['end_date']);
        if ($first < $second) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

function  stopIt($test)
{

    header("HTTP/1.1  409 miniResponse");
    $json = json_encode([
        'null'=>is_null($test),
        'obj'=>is_object($test),
        'array'=>is_array($test),
        'empty'=>empty($test),
        'test' => $test,
        ]);
    echo $json;
    exit();
}