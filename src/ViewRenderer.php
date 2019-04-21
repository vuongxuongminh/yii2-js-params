<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-js-params
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\jsParams;

use Yii;

use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\ViewRenderer as BaseViewRenderer;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

/**
 * Class ViewRenderer support render view file with passed data to javascript.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ViewRenderer extends BaseViewRenderer implements BootstrapInterface
{

    /**
     * @var array store renderer of views with key is hash id of view values is an array store renderer of view ext.
     */
    protected static $renderers = [];

    /**
     * @var array store params need to passed to javascript.
     */
    protected static $jsParams = [];

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        Event::off(View::class, View::EVENT_BEFORE_RENDER, [static::class, 'beforeRender']);
        Event::off(View::class, View::EVENT_END_PAGE, [static::class, 'endPage']);
        Event::on(View::class, View::EVENT_BEFORE_RENDER, [static::class, 'beforeRender']);
        Event::on(View::class, View::EVENT_END_PAGE, [static::class, 'endPage']);
    }

    /**
     * @inheritDoc
     * @throws \Exception
     * @throws \Throwable
     */
    public function render($view, $file, $params)
    {
        $viewId = spl_object_hash($view);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        /** @var BaseViewRenderer $renderer */
        $renderer = static::$renderers[$viewId][$ext] ?? null;
        $jsParams = ArrayHelper::remove($params, 'jsParams', []);
        static::$jsParams[$viewId] = array_merge(static::$jsParams[$viewId] ?? [], $jsParams);

        if ($renderer) {
            $view->renderers[$ext] = $renderer;

            return $renderer->render($view, $file, $params);
        } else {
            unset($view->renderers[$ext]);

            return $view->renderPhpFile($file, $params);
        }
    }

    /**
     * Event trigger for make this class is the renderer of view file.
     *
     * @param Event|\yii\base\ViewEvent $event triggered.
     * @throws \yii\base\InvalidConfigException
     */
    public static function beforeRender(Event $event): void
    {
        /** @var View $view */
        $view = $event->sender;
        $viewId = spl_object_hash($view);
        $ext = pathinfo($event->viewFile, PATHINFO_EXTENSION);

        if (isset($view->renderers[$ext])) {
            static::$renderers[$viewId][$ext] = Instance::ensure($view->renderers[$ext], BaseViewRenderer::class);
        }

        $view->renderers[$ext] = new static;
    }

    /**
     * Event trigger for register js params to [[VIEW::POS_HEAD]].
     *
     * @param Event $event triggered.
     */
    public static function endPage(Event $event): void
    {
        /** @var View $view */
        $view = $event->sender;
        $viewId = spl_object_hash($view);
        $globalParams = $view->params['jsParams'] ?? [];

        if (is_callable($globalParams)) {
            $globalParams = call_user_func($globalParams, $view);
        }

        $jsParams = ArrayHelper::remove(static::$jsParams, $viewId, []);
        $jsParams = array_merge($globalParams, $jsParams);

        $view->registerJs('window.serverParams = ' . Json::htmlEncode($jsParams), VIEW::POS_HEAD);
    }

}
