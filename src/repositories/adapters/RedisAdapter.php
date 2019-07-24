<?php

namespace anatoliy700\robots\repositories\adapters;

use anatoliy700\robots\directives\IDirective;
use yii\redis\ActiveRecord;

/**
 * Class Redis
 *
 * @property $id
 * @property $name
 * @property $prefix
 * @property $key
 *
 * @package anatoliy700\robots\repositories\activeRecordAdapters
 */
class RedisAdapter extends ActiveRecord implements IDirective
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'prefix', 'key'], 'required'],
            [['name', 'prefix', 'key'], 'string'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributes()
    {
        return ['id', 'name', 'prefix', 'key'];
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
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param $key
     * @return IDirective
     */
    public function setKey($key): IDirective
    {
        $this->setAttribute('key', $key);

        return $this;
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
     * @param array $fields
     * @param array $expand
     * @param bool $recursive
     * @return array
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true): array
    {
        return parent::toArray($fields, $expand, $recursive);
    }
}
