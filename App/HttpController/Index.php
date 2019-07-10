<?php
namespace App\HttpController;

use App\HttpController\BaseController;
use App\Common\InstanceList;

class Index extends BaseController {
    function index() {
        $this->writeJson(200, InstanceList::getInstance()->all());
    }
}