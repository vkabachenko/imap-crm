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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
['http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all', 'type' => 'text/css'],
['assets/global/plugins/font-awesome/css/font-awesome.min.css', 'type' => 'text/css'],
['assets/global/plugins/simple-line-icons/simple-line-icons.min.css', 'type' => 'text/css'],
['assets/global/plugins/bootstrap/css/bootstrap.min.css', 'type' => 'text/css'],
['assets/global/plugins/uniform/css/uniform.default.css', 'type' => 'text/css'],
['assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css', 'type' => 'text/css'],
['assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css', 'type' => 'text/css'],
['assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css', 'type' => 'text/css'],
['assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css', 'type' => 'text/css'],
['assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css', 'type' => 'text/css'],
['assets/global/css/components-rounded.css', 'type' => 'text/css'],
['assets/global/plugins/select2/select2.css', 'type' => 'text/css'],
['assets/global/css/plugins.css', 'type' => 'text/css'],
['assets/admin/layout4/css/layout.css', 'type' => 'text/css'],
['assets/admin/layout4/css/themes/light.css', 'type' => 'text/css'],
['assets/admin/layout4/css/custom.css', 'type' => 'text/css'],
['assets/admin/pages/css/tasks.css', 'type' => 'text/css'],
['http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css', 'type' => 'text/css'],
    ];
    public $js = [
'assets/global/plugins/jquery.min.js',
'assets/global/plugins/jquery-migrate.min.js',
'assets/global/plugins/jquery-ui/jquery-ui.min.js',
'assets/global/plugins/bootstrap/js/bootstrap.min.js',
'assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
'assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
'assets/global/plugins/jquery.blockui.min.js',
'assets/global/plugins/jquery.cokie.min.js',
'assets/global/plugins/uniform/jquery.uniform.min.js',
'assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
'assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
'assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
'assets/global/plugins/clockface/js/clockface.js',
'assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js',
'assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js',
'assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
'assets/global/plugins/bootstrap-datepicker/js/components-pickers.js',
'assets/global/plugins/bootstrap-select/bootstrap-select.min.js',
'assets/global/plugins/select2/select2.min.js',
'assets/global/plugins/morris/morris.min.js',
'assets/global/plugins/morris/raphael-min.js',
'assets/global/plugins/jquery.sparkline.min.js',
'assets/global/scripts/metronic.js',
'assets/admin/layout4/scripts/layout.js',
'assets/admin/layout4/scripts/demo.js',
'assets/admin/pages/scripts/index3.js',
'assets/admin/pages/scripts/tasks.js',
'assets/global/plugins/bootbox/bootbox.min.js',
'assets/jquery.maskedinput-1.2.2.js',
'http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
