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
     * @param null $attributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validate($attributeNames = null, $clearErrors = true): bool;
}
