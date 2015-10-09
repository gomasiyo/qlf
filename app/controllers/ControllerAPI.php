<?php

use Phalcon\Mvc\Controller;

class ControllerAPI extends Controller
{

    /**
     *  ステータス用の保管庫
     */
    protected $_status;
    protected $_id;
    protected $_name;
    protected $_token;

    /**
     * ログイン用POST保存
     */
    public function initialize()
    {
        /**
         *  Header 追加
         */
        $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setRawHeader('HTTP/1.1 200 OK');

        /**
         *  ログイン用POST取得
         */
        $this->_id = $this->request->getPost('id', null, null);
        $this->_name = $this->request->getPost('name', null, null);
        $this->_token = $this->request->getPost('token', null, null);

        /**
         *  View 非ロード
         */
        $this->view->disable();

    }

    /**
     *  Tokenチェック
     *  @return boolean
     */
    protected function _checkToken()
    {
        if(!(empty($this->_id) || empty($this->_name) || empty($this->_token))) {
            return $this->security->checkHash($this->_id . '+' . $this->_name, $this->_token);
        } else {
            return false;
        }
    }

}
