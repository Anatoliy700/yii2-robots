<?php

namespace anatoliy700\robots\behaviors;

use anatoliy700\robots\directives\IDirective;
use anatoliy700\robots\generators\IRouteGenerator;
use anatoliy700\robots\repositories\IRepository;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

class RobotsModelBehaviors extends Behavior
{
    /**
     * @var array
     */
    public $route;

    /**
     * @var string
     */
    protected $robotsBlockingFlag = 0;

    /**
     * @var IRepository
     */
    protected $repository;

    /**
     * @var IRouteGenerator
     */
    protected $routeGenerator;

    /**
     * RobotsModelBehaviors constructor.
     * @param IRepository $repository
     * @param IRouteGenerator $routeGenerator
     * @param array $config
     */
    public function __construct(IRepository $repository, IRouteGenerator $routeGenerator, $config = [])
    {
        $this->repository = $repository;
        $this->routeGenerator = $routeGenerator;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'eventHandler',
            ActiveRecord::EVENT_AFTER_UPDATE => 'eventHandler',
            ActiveRecord::EVENT_BEFORE_DELETE => 'eventHandler',
        ];
    }

    /**
     * @param $event
     * @throws InvalidConfigException
     */
    public function eventHandler($event)
    {
        switch ($event->name) {
            case 'afterInsert':
            case 'afterUpdate':
                if (array_key_exists($this->robotsBlockingFlag, $this->repository->getFlagsToNameDirectiveArray())) {
                    $this->addDirective();
                    break;
                }
            case 'beforeDelete':
                $this->deleteDirective();
        }
    }

    /**
     * @throws InvalidConfigException
     */
    protected function addDirective()
    {
        $directive = $this->createDirective(
            $this->repository->getNameDirectiveByFlag($this->robotsBlockingFlag),
            $this->getPrefix()
        );
        if ($directive->validate()) {
            $this->repository->saveDirective($this->getKey(), $directive);
        }
    }

    /**
     *
     */
    protected function deleteDirective()
    {
        $this->repository->deleteDirective($this->getKey());
    }

    /**
     * @return IDirective|null
     */
    protected function fetchDirective(): ?IDirective
    {
        return $this->repository->getDirectiveByKey($this->getKey());
    }

    /**
     * @param $name
     * @param $prefix
     * @return IDirective|object
     * @throws InvalidConfigException
     */
    protected function createDirective($name, $prefix): IDirective
    {
        return Yii::createObject(IDirective::class, [$name, $prefix]);
    }

    /**
     * @return int
     */
    public function getRobotsBlockingFlag(): int
    {
        if ($directive = $this->fetchDirective()) {
            $this->robotsBlockingFlag = $this->repository->getFlagByNameDirective($directive->getName());
        }

        return $this->robotsBlockingFlag;
    }

    /**
     * @param string $flag
     */
    public function setRobotsBlockingFlag(string $flag): void
    {
        $this->robotsBlockingFlag = $flag;
    }

    /**
     * @return string
     */
    public function getRobotsPath(): ?string
    {
        $directive = $this->fetchDirective();

        return $directive ? $directive->getPrefix() : null;
    }

    /**
     * @return string
     * @throws InvalidConfigException
     */
    protected function getPrefix(): string
    {
        return $this->routeGenerator->getPath($this->getRoute());
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    protected function getRoute(): array
    {
        if (!is_array($this->route) || !count($this->route)) {
            throw new InvalidConfigException('Route not set');
        }
        $route = [];

        $route[] = $this->route[0];
        unset($this->route[0]);

        foreach ($this->route as $key => $attrName) {
            $route[$key] = $this->owner->$attrName;
        }

        return $route;
    }

    /**
     * @return string
     */
    protected function getKey(): string
    {
        $key = [
            get_class($this->owner),
            Yii::$app->controller->module->id,
            Yii::$app->controller->id,
            Yii::$app->language,
            $this->owner->id,
        ];

        return implode('', $key);
    }
}
