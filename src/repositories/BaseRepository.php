<?php

namespace anatoliy700\robots\repositories;

use anatoliy700\robots\directives\IDirective;
use yii\base\Component;

abstract class BaseRepository extends Component implements IRepository
{
    /**
     * @param $flag
     * @return string
     */
    public function getNameDirectiveByFlag($flag): string
    {
        return $this->getFlagsToNameDirectiveArray()[$flag];
    }

    /**
     * @param $name
     * @return int
     */
    public function getFlagByNameDirective($name): int
    {
        $reverseArray = array_flip($this->getFlagsToNameDirectiveArray());

        return $reverseArray[$name];
    }

    /**
     * @return array
     */
    public function getFlagsToNameDirectiveArray(): array
    {
        return [
            static::DISALLOW_FLAG => IDirective::DISALLOW,
            static::ALLOW_FLAG => IDirective::ALLOW,
        ];
    }
}
