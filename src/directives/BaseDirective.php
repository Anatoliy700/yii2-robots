<?php

namespace anatoliy700\robots\directives;

use anatoliy700\robots\helpers\RobotsHelper;
use ReflectionException;
use yii\base\Model;

abstract class BaseDirective extends Model implements IDirective
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $prefix;

    /**
     * BaseDirective constructor.
     * @param string $name
     * @param string $prefix
     * @param array $config
     */
    public function __construct(string $name, string $prefix, $config = [])
    {
        $this->name = $name;
        $this->prefix = $prefix;

        parent::__construct($config);
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function rules()
    {
        return [
            [['name', 'prefix'], 'required'],
            [['name'], 'in', 'range' => $this->getValidNames()],
        ];
    }

    /**
     * @return bool
     */
    public function validateProps(): bool
    {
        return parent::validate();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->name . ': ' . $this->prefix;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    protected function getValidNames()
    {
        return array_values(RobotsHelper::classConstantsToArray(
            IDirective::class,
            Model::class
        ));
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $name
     * @return IDirective
     */
    public function setName(string $name): IDirective
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $prefix
     * @return IDirective
     */
    public function setPrefix(string $prefix): IDirective
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return array
     */
    public function toArrayProps(): array
    {
        return parent::toArray();
    }
}
