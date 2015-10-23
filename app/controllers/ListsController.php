<?php
/**
 *  [API]リストに関するクラス
 *
 *  リストを操作に関するAPIをまとめたコントローラー
 *  エンドポイント単位でメゾットを定義
 *  Privateメゾットはプリフィックスにアンダーバー[_]をつける
 *
 *  @access public
 *  @author Goma::NanoHa <goma@goma-gz.net>
 *  @extends ControllerAPI
 */

class ListsController extends ControllerAPI
{

    /**
     *  [POST]リストの登録メゾット
     *
     *  Endpoint POST /apo/addList
     *
     *  @access public
     *  @return JSON Responce
     */
    public function addAction()
    {

        $dashboard_id = $this->request->getPost('dashboard', null, null);
        $list = $this->request->getPost('list', null, null);

        if(empty($list)) {
            $this->_status['status'] = false;
            $this->_status['error'] = 'Argument is not enough';
        }

        $templateList = [
            'title' => null,
            'tags_id' => null,
            'comment' => null,
            'url' => null
        ];
        $conditions = [
            'title',
            'url'
        ];
        if($this->_status['status'] && !$this->_mergeArray($list, $templateList, $conditions)) {
            $this->_status['status'] = false;
            $this->_status['error'] = 'JSON Argument is not enough';
        }

        if($this->_status['status'] && !$this->_checkToken()) {
            $this->_status['status'] = false;
            $this->_status['error'] = 'Login Failed';
        }

        if($this->_status['status'] && !$this->_isURL($list['url'])) {
            $this->_status['status'] = false;
            $this->_status['error'] = 'URL is Not';
        }

        if(!$this->_status['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        if(empty($dashboard_id)) {

            $dashboard = Dashboard::findFirst(
                [
                    'users_id = ?1 AND default = ?2',
                    'bind' => [
                        1 => $this->_id,
                        2 => true
                    ]
                ]
            );

            $dashboard_id = $dashboard->id;

        } else {

            $dashboard = Dashboard::findFirst(
                [
                    'users_id = ?1 AND id = ?2',
                    'bind' => [
                        1 => $this->_id,
                        2 => $dashboard_id
                    ]
                ]
            );

            if(empty($dashboard)) {
                $this->_status['status'] = false;
                $this->_status['error'] = 'DashboardID can not be found';
                return $this->response->setJsonContent($this->_status);
            }

        }

        $urls = new Urls();
        $urls->assign(
            [
                'dashboard_id' => $dashboard_id,
                'tags_id' => $list['tags_id'],
                'title' => $list['title'],
                'comment' => $list['comment'],
                'url' => $list['url']
            ]
        );

        if(!$urls->save()) {
            $this->_status['status'] = false;
            $this->_status['error'] = 'Unknow Error';
        }

        return $this->response->setJsonContent($this->_status);

    }

    /**
     *  Listのマージ及び必要項目のNullチェック
     *
     *  @access private
     *  @param JSON &$list
     *      リスト
     *  @param array $templateList
     *      リストのテンプレート
     *  @param array $conditions
     *      リストの必要項目
     *  @return boolean
     */
    private function _mergeArray(&$list, $templateList, $conditions)
    {
        $json = json_decode($list, true);
        $list = array_merge($templateList, $json);
        $status = true;
        foreach($conditions as $key) {
            if($status) {
                $status = !empty($list[$key]);
            }
        }
        return $status;
    }

    /**
     *  URL チェック
     *
     *  @access private
     *  @param string $url
     *      URL
     *  @return boolean
     */
    private function _isURL($url)
    {
        return (bool)preg_match('/(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)/', $url);
    }

}

