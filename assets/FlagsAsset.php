<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\helpers\Url;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FlagsAsset extends AssetBundle
{
    // public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $sourcePath = '@vendor/components/flag-icon-css';
    public $css = [
        'css/flag-icon.min.css',
    ];

    public $publishOptions = [
        'only' => [
            'assets/*',
            'css/*',
            'flags/*',
            'flags/1x1/*',
            'flags/4x3/*',
        ]
    ];
}
