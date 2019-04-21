<?php
/**
 * @link https://github.com/yiiviet/yii2-payment
 * @copyright Copyright (c) 2017 Yii2VN
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\jsParams;

use Yii;

use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\web\View;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Class TestCase
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0
 */
class TestCase extends BaseTestCase
{


    public function setUp(): void
    {
        parent::setUp();

        $this->mockApplication();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\web\Application'): void
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'test',
            'basePath' => __DIR__,
            'bootstrap' => ['vxm\jsParams\ViewRenderer'],
            'vendorPath' => dirname(__DIR__) . '/vendor',
            'components' => [
                'view' => [
                    'renderers' => [
                        'tpl' => 'yii\smarty\ViewRenderer',
                        'twig' => 'yii\twig\ViewRenderer'
                    ],
                ],
                'request' => [
                    'cookieValidationKey' => 'wefJDF8sfdsfSDefwqdxj9oq',
                    'scriptFile' => __DIR__ . '/index.php',
                    'scriptUrl' => '/index.php',
                    'url' => 'http://abc.test'
                ]
            ],
        ], $config));
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication(): void
    {
        Yii::$app = null;
    }


}
