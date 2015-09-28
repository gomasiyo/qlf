<?php

class RegistarController extends ControllerAPI
{

    public function signupAction()
    {

        /**
         *  POST受け取り
         */
        $name = $this->request->getPost('name');
        $passwd = $this->request->getPost('passwd');
        $email = $this->request->getPost('email');
        $screen_name = $this->request->getPost('screenname');

        if(!(empty($name) || empty($email) || empty($screen_name))) {

            if($this->_checkName($name)) {

                $users = new Users();
                $users->assign(array(
                    'name' => $name,
                    'email' => $email,
                    'screen_name' => $screen_name,
                    'password' => $this->security->hash($passwd)
                ));

                if($users->save()) {

                    $dashbord = new Dashboard();
                    $users = Users::findFirstByName($name);
                    $dashbord->assign(array(
                        'users_id' => $users->id,
                        'title' => 'Default',
                        'default' => 1
                    ));

                    if($dashbord->save()) {
                        $this->_status = array(
                            'status' => true
                        );
                    } else {
                        $this->_status = array(
                            'status' => false,
                            'error' => 'Unknow Error'
                        );
                    }

                } else {
                    $this->_status = array(
                        'status' => false,
                        'error' => 'Unknow Error'
                    );
                }

            } else {
                $this->_status = array(
                    'status' => false,
                    'error' => 'That name is already in use'
                );
            }
        } else {
            $this->_status = array(
                'status' => false,
                'error' => 'Argument is not enough'
            );
        }

        return $this->response->setJsonContent($this->_status);

    }


    /**
     *  Users::Name の 重複チェック
     *  @param (string) $name
     *  @return boolean
     */
    private function _checkName($name = null)
    {
        if(empty($name)) return false;
        $user = Users::findFirstByName($name);
        return empty($user);
    }

}
