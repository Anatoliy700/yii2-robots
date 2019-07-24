<?php

namespace anatoliy700\robots\directives;

interface IDirective
{
    const USER_AGENT = 'User-Agent';
    const ALLOW = 'Allow';
    const DISALLOW = 'Disallow';
    const SITEMAP = 'Sitemap';
    const CLEAN_PARAM = 'Clean-param';
    const CRAWL_DELAY = 'Crawl-delay';

    /**
     * @return string
     */
    public function toString(): string;

    /**
     * @return bool
     */
    public function validate(): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPrefix(): string;

    /**
     * @param string $name
     * @return IDirective
     */
    public function setName(string $name): IDirective;

    /**
     * @param string $prefix
     * @return IDirective
     */
    public function setPrefix(string $prefix): IDirective;

    /**
     * @return array
     */
    public function toArray(): array;
}
