<?php

class AccountController extends ControllerBase
{

    private $_status;

    public function initialize()
    {
        /**
         *  Header 追加
         */
        $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');

        $this->response->setRawHeader('HTTP/1.1 200 OK');
    }

    public function loginAction()
    {

        /**
         * POST受け取り
         */
        $name = $this->request->getPost('name');
        $passwd = $this->request->getPost('passwd');

        if(!empty($name) && !empty($passwd)) {

            $user = Users::findFirst(
                array(
                    'name = ?1 OR email = ?1',
                    'bind' => array(
                        1 => $name
                    )
                )
            );

            if($user) {
                if($this->security->checkHash($passwd, $user->password)) {

                    $this->session->set('account',array(
                        'id' => $user->id,
                        'name' => $user->name,
                        'screen_name' => $user->screen_name,
                    ));

                    /**
                     *  Token作成 hash(id+name)
                     */
                    $token = $this->security->hash($user->id . '+' . $user->name);

                    $this->_status = array(
                        'status' => true,
                        'id' => $user->id,
                        'name' => $user->name,
                        'screen_name' => $user->screen_name,
                        'token' => $token
                    );

                } else {
                    $this->_status = array(
                        'status' => false,
                        'error' => 'Login failed'
                    );
                }
            } else {
                $this->_status = array(
                    'status' => false,
                    'errer' => 'Login failed'
                );
            }

        } else {
            $this->_status = array(
                'status' => false,
                'error' => 'Argument is not enough'
            );
        }

        $this->view->disable();

        return $this->response->setJsonContent($this->_status);

    }

}

