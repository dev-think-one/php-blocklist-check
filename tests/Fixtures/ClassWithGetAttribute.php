<?php

namespace BlockListCheck\Tests\Fixtures;

class ClassWithGetAttribute
{
    protected array $value;

    public function getAttribute(string $key): string
    {
        return $this->value[$key] ?? throw new \InvalidArgumentException("Field [$key] not exists.");
    }

    public function setValue(array $value = [])
    {
        $this->value = $value;
    }
}
