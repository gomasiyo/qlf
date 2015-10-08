<?php

class ListsController extends ControllerAPI
{

    public function addAction()
    {

        /**
         *  POST受け取り
         */
        $dashboard_id = $this->request->getPost('dashboard', null, null);
        $list = $this->request->getPost('list', null, null);

        if(!empty($list)) {

            /**
             *  リストチェック
             */
            $templateList = array(
                'title' => null,
                'tags_id' => null,
                'comment' => null,
                'url' => null
            );
            $conditions = array(
                'title',
                'url'
            );

            if($this->_mergeArray($list, $templateList, $conditions)) {

                if($this->_checkToken()) {

                    if($this->_isURL($list['url'])) {

                        if(empty($dashboard_id)) {

                            $dashboard = Dashboard::findFirst(
                                array(
                                    'users_id = ?1 AND default = ?2',
                                    'bind' => array(
                                        1 => $this->_id,
                                        2 => true
                                    )
                                )
                            );

                            $dashboard_id = $dashboard->id;

                        } else {

                            $dashboard = Dashboard::findFirst(
                                array(
                                    'users_id = ?1 AND id = ?2',
                                    'bind' => array(
                                        1 => $this->_id,
                                        2 => $dashboard_id
                                    )
                                )
                            );

                            if(!empty($dashboard)) {

                            } else {
                                $this->_status = array(
                                    'status' => false,
                                    'error' => 'DashboardID can not be found'
                                );
                            }
                        }

                        $urls = new Urls();
                        $urls->assign(
                            array(
                                'dashboard_id' => $dashboard_id,
                                'tags_id' => $list['tags_id'],
                                'title' => $list['title'],
                                'comment' => $list['comment'],
                                'url' => $list['url']
                            )
                        );


                    } else {
                        $this->_status = array(
                            'status' => false,
                            'error' => 'URL is Not'
                        );
                    }

                } else {
                    $this->_status = array(
                        'status' => false,
                        'error' => 'Login failed'
                    );
                }

            } else {
                $this->_status = array(
                    'status' => false,
                    'error' => 'JSON Argument is not enough'
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
     *  Listのマージ及び必要項目のNullチェック
     *
     *  @access private
     *  @param (Json:使用後Array) &$list
     *  @param (array) $templateList
     *  @param (array) $conditions
     *  @return boolean
     */
    private function _mergeArray(&$list, $templateList, $conditions)
    {
        $json = json_decode($list, true);
        $list = array_merge($templateList, $json);
        $status = true;
        foreach($conditions as $key) {
            $status = !empty($list[$key]);
        }
        return $status;
    }

    /**
     *  URL チェック
     *
     *  @access private
     *  @param (string:URL) $url
     *  @return boolean
     */
    private function _isURL($url)
    {
        return !!preg_match('/(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)/', $url);
    }

}
