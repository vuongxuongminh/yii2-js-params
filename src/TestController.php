<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-js-params
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\jsParams;

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
