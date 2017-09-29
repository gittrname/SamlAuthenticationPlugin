<?php
namespace SamlAuthenticationPlugin\Auth;

use Cake\Controller\ComponentRegistry;
use Cake\Auth\BaseAuthenticate;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use \OneLogin_Saml2_Auth;
use \OneLogin_Saml2_Error;

class SamlAuthenticate extends BaseAuthenticate
{
    private $_saml = null;

    /**
     * [__construct description]
     * @param ComponentRegistry $registry [description]
     * @param array             $config   [description]
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);

        if (!isset($config['saml_config']))
        {
            $config['saml_config'] =  Configure::read('saml_config');
        }

        $this->_saml = new OneLogin_Saml2_Auth($config['saml_config']);
    }

    /**
     * [authenticate description]
     * @param  ServerRequest $request  [description]
     * @param  Response      $response [description]
     * @return [type]                  [description]
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        try {
            $this->_saml->processResponse(
                $request->session()->read('AuthNRequestID')
            );
        } catch (OneLogin_Saml2_Error $e) {
            return $this->unauthenticated($request, $response);
        }

        if (!$this->_saml->isAuthenticated())
        {
            return false;
        }

        $errors = $this->_saml->getErrors();
        if (!empty($errors))
        {
            throw new OneLogin_Saml2_Error(
                'Invalid SP authenticate: '.implode(', ', $errors),
                OneLogin_Saml2_Error::METADATA_SP_INVALID
            );
        }

        $request->session()->delete('AuthNRequestID');
        $request->session()->write('samlUserdata', $this->_saml->getAttributes());
        $request->session()->write('samlNameId', $this->_saml->getNameId());
        $request->session()->write('samlSessionIndex', $this->_saml->getSessionIndex());
        $request->session()->write('samlNameIdFormat', $this->_saml->getNameIdFormat());

        return [
            'NameId' => $this->_saml->getNameId(),
            'NameIdFormat' => $this->_saml->getNameIdFormat(),
            'SessionIndex' => $this->_saml->getSessionIndex(),
            'Attributes' => $this->_saml->getAttributes(),
        ];
    }

    /**
     * [unauthenticated description]
     * @param  ServerRequest $request  [description]
     * @param  Response      $response [description]
     * @return [type]                  [description]
     */
    public function unauthenticated(ServerRequest $request, Response $response)
    {
        return $this->_saml->login();
    }

    /**
     * [logout description]
     * @return [type] [description]
     */
    public function logout($event)
    {
        $controller = $this->_registry->getController();
        try {
            $this->_saml->processSLO(
                false, $controller->request->session()->read('AuthNRequestID')
            );
        } catch (OneLogin_Saml2_Error $e) {
            return $this->_saml->logout(
                null,
                $controller->request->session()->read('samlUserdata'),
                $controller->request->session()->read('samlNameId'),
                $controller->request->session()->read('samlSessionIndex'),
                false,
                $controller->request->session()->read('samlNameIdFormat')
            );
        }

        $errors = $this->_saml->getErrors();
        if (!empty($errors))
        {
            throw new OneLogin_Saml2_Error(
                'Invalid SP logout: '.implode(', ', $errors),
                OneLogin_Saml2_Error::METADATA_SP_INVALID
            );
        }

        $controller->request->session()->delete('AuthNRequestID');
        $controller->request->session()->delete('samlUserdata');
        $controller->request->session()->delete('samlNameId');
        $controller->request->session()->delete('samlSessionIndex');
        $controller->request->session()->delete('samlNameIdFormat');
    }

    /**
     * [getMetadata description]
     * @return [type] [description]
     */
    public function getMetadata()
    {
        $metadata = $this->_saml->getSettings()->getSPMetadata();
        $errors = $this->_saml->getSettings()->validateMetadata($metadata);
        if (!empty($errors)) {
            throw new OneLogin_Saml2_Error(
                'Invalid SP metadata: '.implode(', ', $errors),
                OneLogin_Saml2_Error::METADATA_SP_INVALID
            );
        }
        return $metadata;
    }

    /**
     * [implementedEvents description]
     * @return [type] [description]
     */
    public function implementedEvents()
    {
        return [
            'Auth.logout' => 'logout',
        ];
    }
}
