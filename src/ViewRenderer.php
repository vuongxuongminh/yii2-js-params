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
     * @var array store params need to passed to javascript.
     */
    protected static $jsParams = [];

    /**
     * @var array store renderer of views with key is hash id of view values is an array store renderer of view ext.
     */
    protected static $renderers = [];

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        Event::on(View::class, View::EVENT_BEFORE_RENDER, [$this, 'beforeRender']);
        Event::on(View::class, View::EVENT_END_PAGE, [$this, 'endPage']);
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
        $renderer = static::$renderers[$viewId][$ext];
        $jsParams = ArrayHelper::remove($params, 'jsParams', []);

        if (Yii::$app) {
            $jsParams = array_merge(Yii::$app->params['jsParams'] ?? [], $jsParams);
        }

        static::$jsParams[$viewId] = $jsParams;

        if ($renderer instanceof self) {
            return $view->renderPhpFile($file, $params);
        } else {
            return $renderer->render($view, $file, $params);
        }
    }

    /**
     * Event trigger for make this class is the renderer of view file.
     *
     * @param Event|\yii\base\ViewEvent $event triggered.
     */
    public function beforeRender(Event $event)
    {
        /** @var View $view */
        $view = $event->sender;
        $viewId = spl_object_hash($view);
        $ext = pathinfo($event->viewFile, PATHINFO_EXTENSION);

        static::$renderers[$viewId][$ext] = $view->renderers[$ext] ?? $this;
    }

    /**
     * Event trigger for register js params to [[VIEW::POS_HEAD]].
     *
     * @param Event $event triggered.
     */
    public function endPage(Event $event)
    {
        /** @var View $view */
        $view = $event->sender;
        $viewId = spl_object_hash($view);
        $jsParams = ArrayHelper::remove(static::$jsParams, $viewId, []);

        $view->registerJs('window.params = ' . Json::htmlEncode($jsParams), VIEW::POS_HEAD);
    }


}
