<?php

use Phalcon\Mvc\Controller;

class ControllerAPI extends Controller
{

    /**
     *  ステータス用の保管庫
     */
    protected $_status;

    public function initialize()
    {
        /**
         *  Header 追加
         */
        $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setRawHeader('HTTP/1.1 200 OK');

        /**
         *  View 非ロード
         */
        $this->view->disable();

    }

}
