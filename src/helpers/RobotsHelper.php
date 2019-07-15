<?php

namespace anatoliy700\robots\helpers;

use anatoliy700\robots\repositories\IRepository;
use ReflectionClass;
use ReflectionException;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\di\Instance;

class RobotsHelper
{
    /**
     * @param $className
     * @param string $parentClassExclude
     * @return array
     * @throws ReflectionException
     */
    public static function classConstantsToArray($className, $parentClassExclude = ''): array
    {
        $constant = (new ReflectionClass($className))->getConstants();
        if ($parentClassExclude) {
            $excludeConstant = (new ReflectionClass(Model::class))->getConstants();
            $constant = array_diff($constant, $excludeConstant);
        }

        return array_change_key_case($constant);
    }

    /**
     * @param string $definition
     * @return array
     * @throws InvalidConfigException
     */
    public static function getFlagsToNameDirectiveArray($definition = IRepository::class): array
    {
        if (!Yii::$container->has($definition)) {
            return [];
        }

        $instance = Instance::ensure($definition);

        return $instance->getFlagsToNameDirectiveArray();
    }
}
