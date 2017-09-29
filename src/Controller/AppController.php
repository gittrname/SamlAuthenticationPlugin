<?php
namespace SamlAuthenticationPlugin\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * アプリケーションコントローラー
 */
class AppController extends Controller
{
    /**
     * 初期設定
     */
    public function initialize()
    {
        parent::initialize();
        // 認証機能設定
        $this->loadComponent('Auth', [
            // 認証アクション
            'loginAction' => [
                'controller' => 'Sso',
                'action' => 'login',
            ],
            // 認証後リダイレクト先
            'loginRedirect' => [
                'controller' => 'Sso',
                'action' => 'index'
            ],
            // SAML認証指定
            'authenticate' => ['SamlAuthenticationPlugin.Saml']
        ]);
    }
}
