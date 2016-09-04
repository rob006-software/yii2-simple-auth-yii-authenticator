Helper for authenticate Request from yii2-httpclient package
============================================================

Yii 2 extension that provides helper for authenticate `Request` from
[yii2-httpclient](https://github.com/yiisoft/yii2-httpclient) package.

This is a simple helper for [yii2-simple-auth](https://github.com/rob006/yii2-simple-auth) which
simplify authenticating `Request` object from official Yii 2 httpclient extension.
Read [yii2-simple-auth README](https://github.com/rob006/yii2-simple-auth/blob/master/README.md#configuration)
to get more details about authentication process and configuration.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```shell
php composer.phar require rob006/yii2-simple-auth-yii-authenticator
```

or add

```json
"rob006/yii2-simple-auth-yii-authenticator": "^1.0"
```

to the require section of your `composer.json` file.


Usage
-----


You can simply authenticate `Request` object from official Yii 2 [httpclient](https://github.com/yiisoft/yii2-httpclient)
by using `YiiAuthenticator` helper:

```php
use yii\httpclient\Client;
use rob006\simpleauth\YiiAuthenticator as Authenticator;

$client = new Client();
$request = $client->createRequest()
	->setUrl('http://api.example.com/user/list/')
	->setData(['ids' => '1,2,3,4']);
$request = Authenticator::authenticate($request);
$response = $request->send();
```

By default `Authenticator` sends authentication token in the header of the request. Alternatively
you can send it in GET or POST param of request. Be careful when you using POST method because
this will set request method as POST and all data set by `\yii\httpclient\Request::setData()` will
be sent as POST data and will not be included in the URL.

Authentication by GET param with custom secret key:

```php
use yii\httpclient\Client;
use rob006\simpleauth\YiiAuthenticator as Authenticator;

$client = new Client();
$request = $client->createRequest()
	->setUrl('http://api.example.com/user/list/')
	->setData(['ids' => '1,2,3,4']);
$request = Authenticator::authenticate($request, Authenticator::METHOD_GET, 'mycustomsecretkey');
$response = $request->send();
```

Authentication by POST param:

```php
use yii\httpclient\Client;
use rob006\simpleauth\YiiAuthenticator as Authenticator;

$client = new Client();
$request = $client->createRequest()
	->setUrl('http://api.example.com/user/list/?ids=1,2,3,4');
$request = Authenticator::authenticate($request, Authenticator::METHOD_POST);
$response = $request->send();
```
