# SAML plugin for CakePHP3.x

本プラグインはCakephp3.x系にSAML認証機能を追加するものです。
（SAML認証ライブラリとしてonelogin/php-samlを使用します）


## インストール方法
plugins配下に本ソースを展開してください。

** Gitでの例 **
```
# cd plugins
# git clone https://github.com/gittrname/SamlAuthenticationPlugin.git
```

## 使い方
別途OpenAMやSimpleSAMLPhpなどのidpが必要となりますので別途用意してください。

1. 設定ファイルの作成
```
# cd plugins/SamlAuthenticationPlugin/config
# cp app.default.php app.php
# vi app.php ← 必要箇所を修正
```
※ 設定情報に関しては各idpのマニュアルを参照してください。

2. プラグインの有効化
・bootstrap.php
　最下部に以下を追記
```
Plugin::load("SamlAuthenticationPlugin");
```


## 認証サンプル
ログイン/ログアウトのサンプルが本ソースに付属しています。
サンプルの確認を行う場合はbootstrap.phpの設定を以下の様に変更してください。
```
Plugin::load("SamlAuthenticationPlugin");
　　　　　　↓
Plugin::load("SamlAuthenticationPlugin", ["bootstrap" => true,  "routes" => true]);
```

・SSOログインURL
http://[ホスト]/saml-auth/login
