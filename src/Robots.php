<?php

namespace anatoliy700\robots;

use anatoliy700\robots\directives\IDirective;
use Yii;
use yii\base\InvalidConfigException;

class Robots implements IRobots
{
    /**
     * @var array
     */
    public $directives = [];

    /**
     * @var bool
     */
    public $blockResource = false;

    /**
     * @var bool
     */
    public $validateDirective = true;

    /**
     * @var IDirective[]
     */
    protected $directiveItems;

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function generateContent(): string
    {
        $this->addDirective($this->getUserAgent());

        if ($this->blockResource) {
            $this->addDirective(
                $this->createDirective(IDirective::DISALLOW, static::BLOCK_RESOURCE_PREFIX)
            );

            return $this->getContent();
        }

        $configDirectives = $this->getDirectiveItems($this->directives);

        if ($configDirectives) {
            foreach ($configDirectives as $directive) {
                $this->addDirective($directive);
            }
        }

        return $this->getContent();
    }

    /**
     * @return IDirective
     * @throws InvalidConfigException
     */
    protected function getUserAgent(): IDirective
    {
        if ($prefix = $this->directives[IDirective::USER_AGENT]) {
            unset($this->directives[IDirective::USER_AGENT]);
        } else {
            $prefix = static::USER_AGENT_DEFAULT;
        }
        $directive = $this->createDirective(IDirective::USER_AGENT, $prefix);

        return $directive;
    }

    /**
     * @param array $config
     * @return array
     * @throws InvalidConfigException
     */
    protected function getDirectiveItems(array $config): array
    {
        $directives = [];

        foreach ($config as $name => $prefixes) {
            if (is_array($prefixes)) {
                foreach ($prefixes as $prefix) {
                    $directives[] = $this->createDirective($name, $prefix);
                }
            } else {
                $directives[] = $this->createDirective($name, $prefixes);
            }
        }

        return $directives;
    }

    /**
     * @param IDirective $directive
     * @return $this
     */
    protected function addDirective(IDirective $directive)
    {
        if (!$this->validateDirective || $directive->validate()) {
            $this->directiveItems[] = $directive;
        }

        return $this;
    }

    /**
     * @param $name
     * @param $prefix
     * @return IDirective
     * @throws InvalidConfigException
     */
    protected function createDirective($name, $prefix): IDirective
    {
        return Yii::createObject(IDirective::class, [$name, $prefix]);
    }

    /**
     * @return string
     */
    protected function getContent(): string
    {
        $content = '';

        foreach ($this->directiveItems as $directive) {
            $content .= $directive->toString() . PHP_EOL;
        }

        return $content;
    }
}
