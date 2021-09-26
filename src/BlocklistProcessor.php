<?php

namespace BlockListCheck;

use BlockListCheck\Contracts\BlocklistChecker;
use InvalidArgumentException;

class BlocklistProcessor
{

    /**
     * @var BlocklistChecker[]|array
     */
    protected array $checkers = [];

    /**
     * BlocklistChecker constructor.
     * @param array $checkers
     */
    public function __construct(array $checkers = [])
    {
        $this->checkers = $checkers;
    }

    /**
     * @return array
     */
    public function getCheckers(): array
    {
        return $this->checkers;
    }

    /**
     * @param array $checkers
     *
     * @return static
     */
    public function setCheckers(array $checkers): static
    {
        $this->checkers = $checkers;

        return $this;
    }

    /**
     * @param BlocklistChecker $checker
     * @return static
     */
    public function addChecker(BlocklistChecker $checker): static
    {
        array_push($this->checkers, $checker);

        return $this;
    }

    /**
     * @param $entity
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public function passed($entity): bool
    {
        foreach ($this->checkers as $checker) {
            if (!($checker instanceof BlocklistChecker)) {
                throw new InvalidArgumentException('$checker should implement BlocklistChecker');
            }

            if (!$checker->check($entity)) {
                return false;
            }
        }

        return true;
    }
}
