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
class UpesAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/upes-pensum.css',
    ];
    public $js = [
        'js/jquery-3.4.1.min.js',
        'js/upes-pensum.js',
        'js/collasible-responsive-smartable/jsmartable.min.js'
    ];
    public $depends = [    
        'yii\bootstrap4\BootstrapAsset'
    ];
}
