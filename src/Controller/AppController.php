<?php
namespace SamlAuthenticationPlugin\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

class AppController extends Controller
{
    /**
     * 初期設定
     * @return [type] [description]
     */
    public function initialize()
    {
        parent::initialize();
        // 認証機能設定
        $this->loadComponent('Auth', [
            'loginAction' => [
                'controller' => 'Sso',
                'action' => 'login',
            ],
            'authError' => '認証が必要です。',
            'storage' => 'Session',
            'authenticate' => [
                'SamlAuthenticationPlugin.Saml' => [
                    'fields' => ['username' => 'mail_address'],
                    'userModel' => 'Users',
                    'finder' => 'user'
                ]
            ]
        ]);
    }
}
