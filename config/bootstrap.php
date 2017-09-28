<?php
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;


// プラグインの設定読み込み
try {
   Configure::load('SamlAuthenticationPlugin.app.default', 'default', false);
   Configure::load('SamlAuthenticationPlugin.app', 'default', false);
} catch (\Exception $e) {
   exit($e->getMessage() . "\n");
}
