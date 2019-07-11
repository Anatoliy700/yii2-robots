<?php

namespace anatoliy700\robots;

interface IRobots
{
    const USER_AGENT_DEFAULT = '*';
    const BLOCK_RESOURCE_PREFIX = '/';

    /**
     * @return string
     */
    public function generateContent(): string;
}
