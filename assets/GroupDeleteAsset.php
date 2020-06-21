<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class GroupDeleteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/group-delete.js',
    ];
    public $depends = [
        JqueryAsset::class,
    ];
}
