<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-js-params
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\jsParams;

use Yii;

use yii\smarty\ViewRenderer as SmartyRenderer;
use yii\twig\ViewRenderer as TwigRenderer;

/**
 * Class ViewRendererTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ViewRendererTest extends TestCase
{

    public function testPHPRenderer()
    {
        $view = Yii::$app->getView();
        $result = $view->renderFile(__DIR__ . '/view-test.php', [
            'jsParams' => [
                'a' => 123
            ]
        ]);
        $this->assertContains('window.params = {"a":123}', $result);
        $this->assertNull($view->renderers['php'] ?? null);
    }

    public function testSmartyRenderer()
    {
        $view = Yii::$app->getView();
        $result = $view->renderFile(__DIR__ . '/view-test.tpl', [
            'jsParams' => [
                'a' => 123
            ]
        ]);
        $this->assertContains('window.params = {"a":123}', $result);
        $this->assertTrue($view->renderers['tpl'] instanceof SmartyRenderer);
    }

    public function testTwigRenderer()
    {
        $view = Yii::$app->getView();
        $result = $view->renderFile(__DIR__ . '/view-test.twig', [
            'jsParams' => [
                'a' => 123
            ]
        ]);
        $this->assertContains('window.params = {"a":123}', $result);
        $this->assertTrue($view->renderers['twig'] instanceof TwigRenderer);
    }

}
