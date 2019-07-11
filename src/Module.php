<?php

namespace anatoliy700\robots;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var string
     */
    public $controllerNamespace = 'anatoliy700\robots\controllers';


    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules($this->getRules());
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        return [
            [
                'pattern' => 'robots',
                'route' => $this->id,
                'suffix' => '.txt',
            ],
        ];
    }
}
