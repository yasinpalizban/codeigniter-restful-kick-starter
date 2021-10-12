<?php

namespace Modules\Shared\Enums;
//enum FilterErrorType: string {
//case Jwt = 'jwt';
//case Permission = 'Permission';
//case Csrf = 'csrf';
//case Login = 'login';
//case Url = 'url';
//
//}

class FilterErrorType
{
    const  Jwt = 'jwt';
    const  Permission = 'permission';
    const  Csrf = 'csrf';
    const   Login = 'login';
    const   Activation = 'activation';
    const   Url = 'url';
    const   Throttle = 'throttle';
    const   Cors = 'cors';
    const   DataInput = 'url';
    const   Error = 'error';
    const Content = 'contentNegotiation';

}
