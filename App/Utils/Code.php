<?php

namespace App\Utils;

class Code
{
    /**
     * 状态码类型：
     *      success
     *      error
     *      warning
     */
    const SUCCESS                         =     1000; 
    const ERROR                           =     1001;
    const PARAM_ERROR                     =     1002;
    const REQUEST_METHOD_NOT_ALLOWED      =     1003;
    const SERVER_INTERNAL_ERROR           =     1004;
    const REQUIRE_PARAMS                  =     1005;
    const TIME_STAMP_ERROR                =     1006;
    const SIGNATURE_ERROR                 =     1007;
    const PARAM_MISS                      =     1008;

    const CODEINFO = [
        Code::SUCCESS =>                'Success',
        Code::ERROR =>                  'Error',
        Code::PARAM_ERROR =>            'Param_error',
        Code::SERVER_INTERNAL_ERROR =>  'Server_internal_error',
        Code::TIME_STAMP_ERROR =>       'Timestamp_error',
        Code::SIGNATURE_ERROR =>        'Signature_error',
        Code::PARAM_MISS =>             'Param_miss',
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
