<?php

namespace wzzwx\yii2gii;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use wzzwx\yii2gii\helpers\ConfigHelper;

class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@gii', __DIR__);
        Yii::setAlias('@wzzwx/yii2gii', __DIR__);
        if ($app->hasModule('gii')) {
            $app->getModule('gii')->generators = array_merge($app->getModule('gii')->generators, ConfigHelper::getConfig());
        }
    }
}
