<?php

namespace anatoliy700\robots\repositories;

use anatoliy700\robots\directives\IDirective;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\di\Instance;

class ActiveRecordRepository extends BaseRepository
{
    /**
     * @var ActiveRecordInterface
     */
    public $activeRecord;

    /**
     * @param string $key
     * @param IDirective $directive
     * @return mixed|void
     * @throws InvalidConfigException
     */
    public function saveDirective(string $key, IDirective $directive)
    {
        $arDirective = $this->getDirectiveByKey($key);
        if (is_null($arDirective)) {
            $arDirective = Instance::ensure($this->activeRecord, IDirective::class);
        }
        Yii::configure($arDirective, $directive->toArray());
        $arDirective->key = $key;
        $arDirective->save();
    }

    /**
     * @param $key
     * @return IDirective|null
     */
    public function getDirectiveByKey($key): ?IDirective
    {
        /* @var $arDirective IDirective */
        $arDirective = $this->activeRecord::findOne(['key' => $key]);

        return $arDirective;
    }

    /**
     * @param string $key
     * @return bool|int|mixed
     */
    public function deleteDirective(string $key)
    {
        /* @var $arDirective ActiveRecordInterface */
        $arDirective = $this->getDirectiveByKey($key);
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
