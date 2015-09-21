<?php

class AccountController extends ControllerBase
{

    private $_status;

    public function initialize()
    {

        $this->response->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setRawHeader('HTTP/1.1 200 OK');

        $this->view->disable();

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
                    $this->_status = $user;
                }
            }


        } else {
            $this->_status = array(
                'status' => false,
                'error' => 'Argument is not enough'
            );
        }

        return $this->response->setJsonContent($this->_status);

    }

}

