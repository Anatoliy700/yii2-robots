<?php

namespace anatoliy700\robots\repositories;

use anatoliy700\robots\directives\IDirective;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\di\Instance;

class ActiveRecordRepository extends Component implements IRepository
{
    /**
     * @var ActiveRecordInterface
     */
    public $activeRecord;

    /**
     * @param IDirective $directive
     * @return mixed|void
     * @throws InvalidConfigException
     */
    public function saveDirective(IDirective $directive)
    {
        $arDirective = $this->getDirectiveByPrefix($directive->getPrefix());
        if (is_null($arDirective)) {
            $arDirective = Instance::ensure($this->activeRecord, IDirective::class);
        }
        Yii::configure($arDirective, $directive->toArrayProps());
        $arDirective->save();
    }

    /**
     * @param $prefix
     * @return IDirective|null
     */
    public function getDirectiveByPrefix($prefix): ?IDirective
    {
        /* @var $arDirective IDirective */
        $arDirective = $this->activeRecord::findOne(['prefix' => $prefix]);

        return $arDirective;
    }

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


    /**
     * @param string $prefix
     * @return bool|int|mixed
     */
    public function deleteDirective(string $prefix)
    {
        /* @var $arDirective ActiveRecordInterface */
        $arDirective = $this->getDirectiveByPrefix($prefix);
        if ($arDirective) {
            return $arDirective->delete();
        }

        return false;
    }

    /**
     * @return IDirective[]
     */
    public function fetchAll(): array
    {
        return $this->activeRecord::find()->all();
    }
}
