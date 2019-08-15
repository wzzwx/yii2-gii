<?php

namespace wzzwx\yii2gii\helpers;

use Yii;

class ConfigHelper
{
    public static function getConfig()
    {
        $config = [
            'ajaxcrud' => [
                'class' => 'johnitvn\ajaxcrud\generators\Generator',
                'templates' => [
                    'default' => '@vendor/wzzwx/yii2-gii/src/template/ajaxcrud',
                ],
            ],
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'default' => '@vendor/wzzwx/yii2-gii/src/template/crud',
                ],
            ],
            'controller' => [
                'class' => 'yii\gii\generators\controller\Generator',
                'templates' => [
                    'default' => '@vendor/wzzwx/yii2-gii/src/template/controller',
                ],
            ],
            'model' => [
                'class' => 'yii\gii\generators\model\Generator',
                'templates' => [
                    'default' => '@vendor/wzzwx/yii2-gii/src/template/model',
                ],
                'ns' => "common\models",
                'generateLabelsFromComments' => true,
            ],
        ];
        return $config;
    }
}
