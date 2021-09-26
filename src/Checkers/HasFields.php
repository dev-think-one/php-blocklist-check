<?php

namespace BlockListCheck\Checkers;

trait HasFields
{

    /**
     * Fields to check.
     *
     * @var array
     */
    protected array $fields = [];

    /**
     * Fields to check.
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set fields.
     *
     * @param array $fields
     *
     * @return static
     */
    public function setFields(array $fields): static
    {
        $this->fields = array_unique($fields);

        return $this;
    }

    /**
     * Add field to list.
     *
     * @param string $field
     *
     * @return static
     */
    public function addField(string $field): static
    {
        array_push($this->fields, $field);

        $this->fields = array_unique($this->fields);

        return $this;
    }
}
