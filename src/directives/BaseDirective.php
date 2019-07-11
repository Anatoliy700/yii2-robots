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
    protected $name;

    /**
     * @var string
     */
    protected $prefix;

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
        return parent::validate($attributeNames, $clearErrors);
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
        $constant = (new ReflectionClass(static::class))->getConstants();
        $excludeConstant = (new ReflectionClass(Model::class))->getConstants();

        return array_values(array_diff($constant, $excludeConstant));
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
}
