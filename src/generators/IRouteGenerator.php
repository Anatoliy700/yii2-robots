<?php

namespace anatoliy700\robots\generators;

interface IRouteGenerator
{
    /**
     * @param array $route
     * @return string
     */
    public function getPath(array $route): string;
}
