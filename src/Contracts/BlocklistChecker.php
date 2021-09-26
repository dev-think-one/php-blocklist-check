<?php

namespace BlockListCheck\Contracts;

interface BlocklistChecker
{
    /**
     * Check is entity should be blocklisted.
     *
     * @param mixed $entity
     *
     * @return bool
     */
    public function check(mixed $entity): bool;
}
