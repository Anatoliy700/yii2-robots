<?php

namespace anatoliy700\robots\directives;

use ReflectionClass;
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
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true): bool
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
        $constant = (new ReflectionClass(IDirective::class))->getConstants();
        $excludeConstant = (new ReflectionClass(Model::class))->getConstants();
        $constant = array_diff($constant, $excludeConstant);

        return array_values($constant);
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
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        return parent::toArray();
    }
}
