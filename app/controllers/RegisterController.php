<?php
/**
 *  [API]アカウントの登録、変更に関するクラス
 *
 *  アカウント操作に関するAPIをまとめたコントローラー
 *  エンドポイント単位でメゾットを定義
 *  Privateメゾットはプリフィックスにアンダーバー[_]をつける
 *
 *  @access public
 *  @author Goma::NanoHa <goma@goma-gz.net>
 *  @extends ControllerAPI
 */

class RegisterController extends ControllerAPI
{

    /**
     *  [POST]アカウントの登録メゾット
     *
     *  Endpoint POST /api/account/signup
     *
     *  @access public
     *  @return JSON Responce
     */
    public function signupAction()
    {

        $post = [
            'name' => true,
            'email' => true,
            'passwd' => true,
            'screenname' => true
        ];
        if($this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }
        if($this->_status['response']['status'] && !$this->_checkOverlap($this->_post['name'], $this->_post['email'], $detail)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 205;
            $this->_status['response']['detail'] = $detail;
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $users = new Users();
        $users->assign(
            [
                'name' => $this->_post['name'],
                'email' => $this->_post['email'],
                'screen_name' => $this->_post['screenname'],
                'password' => $this->security->hash($this->_post['passwd'])
            ]
        );

        if(!$users->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
            return $this->response->setJsonContent($this->_status);
        }

        $dashboard = new Dashboard();
        $dashboard->assign(array(
            'users_id' => $users->id,
            'title' => 'Default',
            'default' => 1
        ));

        if(!$dashboard->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
            return $this->response->setJsonContent($this->_status);
        }


        return $this->response->setJsonContent($this->_status);

    }


    /**
     *  Name, Email の 重複チェック
     *
     *  @param string $name
     *      名前
     *  @param string $email
     *      メールアドレス
     *  @param array $detail = array()
     *      詳細
     *  @return boolean
     */
    private function _checkOverlap($name, $email, &$detail = [])
    {
        if(empty($name) || empty($email)) return false;
        $users_name = (bool)Users::findFirstByName($name);
        $users_email = (bool)Users::findFirstByEmail($email);
        if($users_name) $detail[] = $name;
        if($users_email) $detail[] = $email;
        return empty($detail);
    }

 }
