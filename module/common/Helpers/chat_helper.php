<?php

// uasort($result, "compareChatRowsByDate");

//        $mererg = array_merge($chat, $reply);
//        uasort($mererg, "my_sort");
function compareChatRowsByDate($a, $b): int
{
    if ($a['createdAt'] == $b['createdAt']) {
        return 0;
    } else if ($a['createdAt'] >= $b['createdAt']) {
        return -1;
    } else if ($a['createdAt'] <= $b['createdAt']) {
        return 1;
    }
}

function compareLastChat($a, $b): int
{


    if ($a->lastChat->date == $b->lastChat->date) {
        return 0;
    } else if ($a->lastChat->date >= $b->lastChat->date) {
        return -1;
    } else if ($a->lastChat->date <= $b->lastChat->date) {
        return 1;
    }
}

function removeObjectChat(array $arr): array
{
    $box = array();
    foreach ($arr as $item) {
        array_push($box, $item);
    }
    return $box;
}
