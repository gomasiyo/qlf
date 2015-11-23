<?php
/**
 *  [API]ダッシュボードに関するクラス
 *  ダッシュボード操作に関するAPIをまとめたコントローラー
 *  エンドポイント単位でメゾットを定義
 *  Privateメゾットはプリフィックスにアンダーバー[_]をつける
 *
 *  @access public
 *  @author Goma::NanoHa <goma@goma-gz.net>
 *  @extends ControllerAPI
 */

class DashboardController extends ControllerAPI
{

    /**
     *  [POST]ダッシュボードの作成メソッド
     *
     *  Endpoint POST /api/dashboad/create
     *
     *  @access public
     *  @return JSON Responce
     */
    public function createAction()
    {

        if($this->_status['response']['status'] && !$this->_checkToken()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 301;
        }

        $post = [
            'detail' => true
        ];
        if($this->_status['response']['status'] && !$this->_getPost($post)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 201;
            $this->_status['response']['detail'] = $post['empty'];
        }

        $templateList = [
            'title' => null,
            'comments' => null
        ];
        $conditions = [
            'title'
        ];
        if($this->_status['response']['status'] && !$this->_mergeArray($this->_post['detail'], $templateList, $conditions)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 202;
            $this->_status['response']['detail'] = $conditions;
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $title = Dashboard::findFirst(
            [
                'users_id = ?1 AND title = ?2',
                'bind' => [
                    1 => $this->_id,
                    2 => $this->_post['detail']['title']
                ]
            ]
        );
        if(!empty($title)) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 206;
            return $this->response->setJsonContent($this->_status);
        }

        $dashboard = new Dashboard();
        $dashboard->assign(
            [
                'users_id' => $this->_id,
                'title' => $this->_post['detail']['title'],
                'comments' => $this->_post['detail']['comments']
            ]
        );

        if(!$dashboard->save()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 102;
            return $this->response->setJsonContent($this->_status);
        }

        return $this->response->setJsonContent($this->_status);

    }

    /**
     *  [POST]ダッシュボードのマージメソッド
     *
     *  Endpoint POST /api/dashboad/marge/:int(origin)/:int(target)
     *
     *  @access public
     *  @return JSON Responce
     */
    public function margeAction()
    {

        if($this->_status['response']['status'] && !$this->_checkToken()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 301;
        }

        if($this->_status['response']['status'] && (!$this->_checkDashboard($this->dispatcher->getParam('origin')) || !$this->_checkDashboard($this->dispatcher->getParam('target')))) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 101;
        }

        if(!$this->_status['response']['status']) {
            return $this->response->setJsonContent($this->_status);
        }

        $urls = Urls::findByDashboard_id($this->dispatcher->getParam('target'));

        if($urls->count() == 0) {
                $this->_status['response']['status'] = false;
                $this->_status['response']['code'] = 103;
                return $this->response->setJsonContent($this->_status);
        }

        foreach($urls as $url) {
            $url->dashboard_id = $this->dispatcher->getParam('origin');
            if(!$url->save()) {
                $this->_status['response']['status'] = false;
                $this->_status['response']['code'] = 102;
                return $this->response->setJsonContent($this->_status);
            }
        }

        return $this->response->setJsonContent($this->_status);

    }

    /**
     *  ダッシュボード詳細チェック
     *
     *  @param int $id
     *  @access private
     *  @return boolean
     */
    private function _checkDashboard($id = null)
    {
        if(empty($id)) return false;
        $dashboard = Dashboard::findFirst(
            [
                    'users_id = ?1 AND id = ?2',
                    'bind' => [
                        1 => $this->_id,
                        2 => $id
                    ]
            ]
        );
        return !empty($dashboard);
    }

}
