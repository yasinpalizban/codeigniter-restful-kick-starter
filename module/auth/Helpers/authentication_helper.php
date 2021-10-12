<?php

function loginVia(string $name): string
{
    if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
        return 'email';
    } else if (is_numeric($name)) {
        return 'phone';
    } else {
        return 'username';
    }
}


function httpPermissionRoleType(string $method): array
{

    switch ($method) {

        case "post":
            return ["get-post-put-delete", "get-post-delete", "post-put-delete", "get-post-put", "get-post", "post-put", "post-delete", "post"];
        case "put":
            return ["get-post-put-delete", "get-put-delete", "post-put-delete", "get-post-put", "get-put", "post-put", "put-delete", "put"];
        case "delete":
            return ["get-post-put-delete", "get-post-delete", "get-put-delete", "post-put-delete", "get-delete", "post-delete", "put-delete", "delete"];
        default:
            return ["get-post-put-delete", "get-post-put", "get-post-delete", "get-put-delete", "get-post", "get-put", "get-delete", "get"];
    }


}


