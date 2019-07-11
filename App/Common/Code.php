<?php

namespace App\Common;

class Code
{
    /**
     * 状态码类型：
     *      success
     *      error
     *      warning
     */
    const SUCCESS                         =   0;

    const ERROR                           =   1;
    const PARAM_ERROR                     =   2;
    const REQUEST_METHOD_NOT_ALLOWED      =   405;
    const SERVER_INTERNAL_ERROR           =   500;

    const CODEINFO = [
        Code::SUCCESS => 'Success',
        Code::ERROR => 'Error',
        Code::PARAM_ERROR => 'Param_error',
        Code::SERVER_INTERNAL_ERROR => 'Server_internal_error'
    ];

    static function msg($code)
    {
        return self::CODEINFO[$code];
    }

    static function status($code) {
        if ($code === Code::SUCCESS) {
            return 'Success';
        } elseif ($code > Code::SUCCESS) {
            return 'Error';
        }
        return 'Warning';
    }
}
