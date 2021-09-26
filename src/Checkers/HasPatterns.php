<?php

namespace BlockListCheck\Checkers;

trait HasPatterns
{

    /**
     * Patterns list to check.
     *
     * @var array
     */
    protected array $patterns = [];

    /**
     * Patterns list to check.
     *
     * @return array
     */
    public function getPatterns(): array
    {
        return $this->patterns;
    }

    /**
     * Set patterns list to check.
     *
     * @param array $patterns
     *
     * @return static
     */
    public function setPatterns(array $patterns): static
    {
        $this->patterns = array_unique($patterns);

        return $this;
    }

    /**
     * Add pattern to list.
     *
     * @param string $pattern
     *
     * @return static
     */
    public function addPattern(string $pattern): static
    {
        array_push($this->patterns, $pattern);

        $this->patterns = array_unique($this->patterns);

        return $this;
    }
}
