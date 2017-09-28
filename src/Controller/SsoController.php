<?php
namespace SamlAuthenticationPlugin\Controller;

use Cake\Event\Event;

class SsoController extends AppController
{
    /**
    * [beforeFilter description]
    * @return [type] [description]
    */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // 認証除外設定
        $this->Auth->allow(['login', 'logout', 'metadata']);
    }

    /**
     * [login description]
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
            $this->Flash->error(__('シングルサインオン出来ません。通常ログインをご検討ください。'));
        }

        return $this->redirect($this->Auth->redirectUrl());
    }

    /**
     * [logout description]
     * @return [type] [description]
     */
    public function logout()
    {
        $this->Auth->logout();
    }

    /**
     * [metadata description]
     * @return [type] [description]
     */
    public function metadata()
    {
        $response = $this->response;
        $response->type('xml');
        $response->body(
            $this->Auth->getAuthenticate('Saml')->getMetadata()
        );

        return $response;
    }
}
