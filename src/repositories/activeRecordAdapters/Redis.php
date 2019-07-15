<?php

namespace anatoliy700\robots\repositories\activeRecordAdapters;

use anatoliy700\robots\directives\IDirective;
use yii\redis\ActiveRecord;

/**
 * Class Redis
 *
 * @property $id
 * @property $name
 * @property $prefix
 *
 * @package anatoliy700\robots\repositories\activeRecordAdapters
 */
class Redis extends ActiveRecord implements IDirective
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'prefix'], 'required'],
            [['name', 'prefix'], 'string'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['id', 'name', 'prefix'];
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->name . ': ' . $this->prefix;
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
        $this->setAttribute('name', $name);

        return $this;
    }

    /**
     * @param string $prefix
     * @return IDirective
     */
    public function setPrefix(string $prefix): IDirective
    {
        $this->setAttribute('prefix', $prefix);

        return $this;
    }

    /**
     * @return bool
     */
    public function validateProps(): bool
    {
        return parent::validate();
    }

    /**
     * @return array
     */
    public function toArrayProps(): array
    {
        return parent::toArray();
    }
}
