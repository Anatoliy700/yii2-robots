<?php

namespace anatoliy700\robots\repositories;

use anatoliy700\robots\directives\IDirective;

class NullRepository extends BaseRepository
{
    /**
     * @param string $key
     * @param IDirective $directive
     * @return bool|mixed
     */
    public function saveDirective(string $key, IDirective $directive)
    {
        return false;
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function deleteDirective(string $key)
    {
        return false;
    }

    /**
     * @param $key
     * @return IDirective|null
     */
    public function getDirectiveByKey($key): ?IDirective
    {
        return null;
    }

    /**
     * @return IDirective[]
     */
    public function fetchAll(): array
    {
        return [];
    }
}
