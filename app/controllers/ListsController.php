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
     *  Endpoint POST /api/lists/add
     *
     *  @access public
     *  @return JSON Responce
     */
    public function addAction()
    {

        $post = [
            'dashboard' => false,
            'list' => true
        ];
        if($this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
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
        if($this->_status['response']['status'] && !$this->_mergeArray($this->_post['list'], $templateList, $conditions)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = '202';
            $this->_status['response']['detail'] = $conditions;
        }

        if($this->_status['response']['status'] && $this->_checkToken()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 301;
        }

        if($this->_status['response']['status'] && !$this->_isURL($this->_post['list']['url'])) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 203;
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        if(empty($dashboard_id)) {

            $dashboard = Dashboard::findFirst(
                [
                    'users_id = ?1 AND default = true',
                    'bind' => [
                        1 => $this->_id,
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
                $this->_status['response']['status'] = false;
                $this->_status['response']['code'] = 204;
                return $this->response->setJsonContent($this->_status);
            }

        }

        $urls = new Urls();
        $urls->assign(
            [
                'dashboard_id' => $dashboard_id,
                'title' => $this->_post['list']['title'],
                'comment' => $this->_post['list']['comment'],
                'url' => $this->_post['list']['url']
            ]
        );

        if(!$urls->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
        }

        if(!empty($this->_post['list']['tag'])) {

            foreach($this->_post['list']['tag'] as $tag) {

                $tags = new Tags();
                $tags->assign(
                    [
                        'tag' => $tag,
                        'urls_id' => $urls->id
                    ]
                );

                if(!$tags->save()) {
                    $this->_status['response']['status'] = false;
                    $this->_status['response']['code'] = 102;
                    return $this->response->setJsonContent($this->_status);
                }

            }

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
     *  @param array &$conditions
     *      リストの必要項目
     *  @return boolean
     */
    private function _mergeArray(&$list, $templateList, &$conditions)
    {
        $json = json_decode($list, true);
        $list = array_merge($templateList, $json);
        $status = [];
        foreach($conditions as $key) {
            if(empty($list[$key])) $status[] = $key;
        }
        $conditions = $status;
        return empty($status);
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

