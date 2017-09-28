<?php
return [
    /**
     * SAML認証設定
     */
    'saml_config' => [
        'strict' => false,
        'debug' => false,
        'baseurl' => '__BASE_URL__',
        'sp' => [
            'entityId' => '__SP_ENTITY_ID__',
            'assertionConsumerService' => [
                'url' => '__SP_CONSUMER_SERVICE_URL__',
            ],
            'singleLogoutService' => [
                'url' => '__SP_LOGOUT_SERVICE_URL__',
            ],
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
            'x509cert' => '__SP_X509_CERT__',
            'privateKey' => '__SP_PRIVATE_KEY__'
        ],
        'idp' => [
            'entityId' => '__IDP_ENTITY_ID__',
            'singleSignOnService' => [
                'url' => '__IDP_SSO_SERVICE_URL__',
            ],
            'singleLogoutService' => [
                'url' => '__IDP_SLO_SERVICE_URL__',
            ],
            'x509cert' => '__IDP_X509_CERT__'],
        'security' => [
            'authnRequestsSigned' => true,
        ]
    ]
];
