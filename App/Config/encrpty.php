<?php

return [
    'AES_ALG' => getenv('AES_ALG') ?: 'aes-256-cbc',
    'AES_KEY' => getenv('AES_KEY') ?: 'wtf',
    'AES_IV' => getenv('AES_IV') ?: '1111111111111111',
];