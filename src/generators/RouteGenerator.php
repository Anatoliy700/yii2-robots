<?php

namespace anatoliy700\robots\generators;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\web\UrlManager;

class RouteGenerator extends BaseObject implements IRouteGenerator
{
    /**
     * @var UrlManager
     */
    public $urlManager;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->urlManager = Instance::ensure($this->urlManager, UrlManager::class);
    }

    /**
     * @param array $route
     * @return string
     */
    public function getPath(array $route): string
    {
        return $this->urlManager->createUrl($route);
    }
}
