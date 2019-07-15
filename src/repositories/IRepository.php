<?php

namespace anatoliy700\robots\repositories;

use anatoliy700\robots\directives\IDirective;

interface IRepository
{
    const DELETE_DIRECTIVE_FLAG = 0;
    const DISALLOW_FLAG = 1;
    const ALLOW_FLAG = 2;

    /**
     * @param IDirective $directive
     * @return mixed
     */
    public function saveDirective(IDirective $directive);

    /**
     * @param string $prefix
     * @return mixed
     */
    public function deleteDirective(string $prefix);

    /**
     * @param $prefix
     * @return IDirective|null
     */
    public function getDirectiveByPrefix($prefix): ?IDirective;

    /**
     * @param $flag
     * @return string
     */
    public function getNameDirectiveByFlag($flag): string;

    /**
     * @param $name
     * @return int
     */
    public function getFlagByNameDirective($name): int;

    /**
     * @return array
     */
    public function getFlagsToNameDirectiveArray(): array;

    /**
     * @return IDirective[]
     */
    public function fetchAll(): array;
}
