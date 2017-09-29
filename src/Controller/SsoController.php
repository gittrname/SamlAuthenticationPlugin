<?php
namespace SamlAuthenticationPlugin\Controller;

use Cake\Event\Event;

/**
 * SSO認証コントローラー
 */
class SsoController extends AppController
{
    /**
    * フィルタ実行前処理
    * @return [type] [description]
    */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // 認証除外設定
        $this->Auth->allow(['login', 'logout', 'metadata']);
    }

    /**
     * 認証後参照可能となる画面
     * @return [type] [description]
     */
    public function index()
    {
        $this->set('user', $this->Auth->user());
        $this->render('SamlAuthenticationPlugin.index');
    }

    /**
     * 認証処理
     * @return [type] [description]
     */
    public function login()
    {
        $user = $this->Auth->identify();
        if ($user)
        {
            $this->Auth->setUser($user);
        }
        else
        {
            $this->Flash->error(__('シングルサインオン出来ません。'));
        }

        return $this->redirect($this->Auth->redirectUrl());
    }

    /**
     * ログアウト処理
     * @return [type] [description]
     */
    public function logout()
    {
        $this->Auth->logout();
        $this->render('SamlAuthenticationPlugin.logout');
    }

    /**
     * メタデータXML出力処理
     * @return [type] [description]
     */
    public function metadata()
    {
        $response = $this->response;
        $response->type('xml');
        $response->body(
            $this->Auth->getAuthenticate('SamlAuthenticationPlugin.Saml')->getMetadata()
        );

        return $response;
    }
}
