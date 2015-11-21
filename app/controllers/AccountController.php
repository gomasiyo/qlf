<?php
/**
 *  [API]アカウントに関するクラス
 *
 *  アカウントに関するAPIをまとめたコントローラー
 *  エンドポイント単位でメゾットを定義
 *  Privateメゾットはプリフィックスにアンダーバー[_]をつける
 *
 *  @access public
 *  @author Goma::NanoHa <goma@goma-gz.net>
 *  @extends ControllerAPI
 */

class AccountController extends ControllerAPI
{

    /**
     *  [POST]ログインメゾット
     *
     *  Endpoint POST /api/account/login
     *
     *  @access public
     *  @return JSON Responce
     */
    public function loginAction()
    {

        $post = [
            'name' => true,
            'passwd' => true
        ];
        if($this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }


        $user = Users::findFirst(
            [
                'name = ?1 OR email = ?1',
                'bind' => array[
                    1 => $this->_post['name']
                ]
            ]
        );

        if(!$user || !$this->security->checkHash($this->_post['passwd'], $user->password)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 302;
            return $this->response->setJsonContent($this->_status);
        }


        $token = $this->security->hash($user->id . '+' . $user->name);

        $result = [
            'status' => true,
            'id' => $user->id,
            'name' => $user->name,
            'screen_name' => $user->screen_name,
            'token' => $token
        ];

        $this->_status['result'] = $result;

        return $this->response->setJsonContent($this->_status);

    }

}

