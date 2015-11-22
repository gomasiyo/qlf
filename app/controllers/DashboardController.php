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

        if($this->_status['response']['status'] && $this->_checkToken()) {
            $this->_status['response']['status'] = false;
            $this->_status['response']['code'] = 301;
        }

        $post = [
            'detail' => true
        ];
        if($this->_status['response']['status'] && $this->_getPost($post)) {
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

}
