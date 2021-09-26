<?php

namespace BlockListCheck\Tests\Fixtures;

class ClassWithBlocklistCheckValue
{
    protected array $value;

    public function blocklistCheckValue(string $key): string
    {
        return $this->value[$key] ?? throw new \InvalidArgumentException("Field [$key] not exists.");
    }

    public function setValue(array $value = [])
    {
        $this->value = $value;
    }
}
