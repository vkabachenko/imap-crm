<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        ['http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all'],
['assets/global/plugins/font-awesome/css/font-awesome.min.css'],
['assets/global/plugins/simple-line-icons/simple-line-icons.min.css'],
['assets/global/plugins/bootstrap/css/bootstrap.min.css'],
['assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'],
['assets/global/plugins/select2/css/select2.min.css'],
['assets/global/plugins/select2/css/select2-bootstrap.min.css'],
['assets/global/css/components.min.css'],
['assets/global/css/plugins.min.css'],
['assets/pages/css/login-4.min.css'],
    ];
    public $js = [
['assets/global/plugins/jquery.min.js'],
['assets/global/plugins/bootstrap/js/bootstrap.min.js'],
['assets/global/plugins/js.cookie.min.js'],
['assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'],
['assets/global/plugins/jquery.blockui.min.js'],
['assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'],
['assets/global/plugins/jquery-validation/js/jquery.validate.min.js'],
['assets/global/plugins/jquery-validation/js/additional-methods.min.js'],
['assets/global/plugins/select2/js/select2.full.min.js'],
['assets/global/plugins/backstretch/jquery.backstretch.min.js'],
['assets/global/scripts/app.min.js'],
['assets/pages/scripts/login-4.min.js']
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset'],
    ];
}
