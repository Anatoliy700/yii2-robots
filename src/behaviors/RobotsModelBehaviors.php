<?php

namespace anatoliy700\robots\behaviors;

use anatoliy700\robots\directives\IDirective;
use anatoliy700\robots\repositories\IRepository;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

class RobotsModelBehaviors extends Behavior
{
    /**
     * @var string
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
     * RobotsModelBehaviors constructor.
     * @param IRepository $repository
     * @param array $config
     */
    public function __construct(IRepository $repository, $config = [])
    {
        $this->repository = $repository;
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
                if (array_key_exists($this->robotsBlockingFlag, $this->getFlagsToNameDirectiveArray())) {
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
        if ($directive->validateProps()) {
            $this->repository->saveDirective($directive);
        }
    }

    /**
     * @throws InvalidConfigException
     */
    protected function deleteDirective()
    {
        $this->repository->deleteDirective($this->getPrefix());
    }

    /**
     * @return IDirective|null
     * @throws InvalidConfigException
     */
    protected function fetchDirective(): ?IDirective
    {
        return $this->repository->getDirectiveByPrefix($this->getPrefix());
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
     * @throws InvalidConfigException
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
     * @throws InvalidConfigException
     */
    protected function getPrefix(): string
    {
        if (is_null($this->route)) {
            throw new InvalidConfigException('Route not set');
        }

        $pattern = '/[\w|\/]+(?<replace_alias><(?<alias>\w+)>)[\w|\/]*/';
        $match = [];
        preg_match($pattern, $this->route, $match);
        if (isset($match['replace_alias']) && isset($match['alias'])) {
            $alias = $match['alias'];
            $replacement = [
                $match['replace_alias'] => $this->owner->$alias,
            ];
            $route = strtr($this->route, $replacement);
        } else {
            $route = $this->route;
        }

        return $route;
    }

    /**
     * @return array
     */
    public function getFlagsToNameDirectiveArray(): array
    {
        return $this->repository->getFlagsToNameDirectiveArray();
    }
}
