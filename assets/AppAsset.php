<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        // 'css/prettify.min.css',
        'fontawesome/css/all.css',
        'fontawesome/css/brands.css',
        'fontawesome/css/fontawesome.css',
        'wow/css/libs/animate.css',
        'wow/css/site.css'
    ];
    public $js = [
        'js/main.js',
        'js/canvasjs.js',
        // 'highcharts/code/highcharts.js',
        // 'highcharts/code/modules/exporting.js',
        // 'highcharts/code/modules/export-data.js',
        // 'highcharts/code/modules/accessibility.js',
        'morrisjs/morris.js',
        // 'js/canvasjs.min.js',
        // 'canvasjs-2.3.1/canvasjs.min.js',
        // 'canvasjs-2.3.1/jquery.canvasjs.min.js',
        // 'morrisjs/morris.min.js',
        'js/raphael-min.js',
        // 'js/prettify.min.js',
        'wow/dist/wow.min.js',
        'js/webcam.js'
        // 'js/webcam.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
