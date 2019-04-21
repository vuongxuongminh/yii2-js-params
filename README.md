# Yii2 Js Params

[![Latest Stable Version](https://poser.pugx.org/vxm/yii2-js-params/v/stable)](https://packagist.org/packages/vxm/yii2-js-params)
[![Total Downloads](https://poser.pugx.org/vxm/yii2-js-params/downloads)](https://packagist.org/packages/vxm/yii2-js-params)
[![Build Status](https://travis-ci.org/vuongxuongminh/yii2-js-params.svg?branch=master)](https://travis-ci.org/vuongxuongminh/yii2-js-params)
[![Code Coverage](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-js-params/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-js-params/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-js-params/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-js-params/?branch=master)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

## About it

An extension provide an easy way to passed variables from your server to the JavaScript in rendering process of Yii2 view component.

## Requirements

* [PHP >= 7.1](http://php.net)
* [yiisoft/yii2 >= 2.0.13](https://github.com/yiisoft/yii2)

## Installation

Require Yii2 JS Prams using [Composer](https://getcomposer.org):

```bash
composer require vxm/yii2-js-params
```

## Usage

You can passed any variables you want when render view with addition `jsParams` element in view params:

```php
use yii\web\Controller;

class TestController extends Controller
{

    public function actionTest()
    {
        return $this->render('test', [
            'jsParams' => [
                'test' => 'vxm'
            ]
        ]);
    }
}
```

And get this data on the frontend side from window.serverParams.

<p align="center">
    <img src="resource/demo.png" width="100%">
</p>

> Note: all variables will passed at View::POS_HEAD please make sure a definition (`$this->head()`) on your layout file.

### Global params

Sometime you need to passed some params to all of view file, you can config it in your app config file:

```php
'components' => [
    'view' => [
        'params' => [
            'jsParams' => ['test' => 'vxm']
        ]
    ]
]
```

Or config an anonymous function:

```php
'components' => [
    'view' => [
        'params' => [
            'jsParams' => function() {
            
                return ['identity' => Yii::$app->user->identity->toArray()]
            }
        ]
    ]
]
```
