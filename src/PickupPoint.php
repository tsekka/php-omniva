<?php

namespace Tsekka\Omniva;

class PickupPoint
{
    const TYPE_TERMINAL = 0;

    const TYPE_POST_OFFICE = 1;

    private $type;

    public $offloadPostcode;

    public function __construct(string $offloadPostcode, int $type)
    {
        $this->offloadPostcode = $offloadPostcode;
        $this->setType($type);
    }

    public function setType(int $type): self
    {
        if (! in_array($type, [self::TYPE_TERMINAL, self::TYPE_POST_OFFICE])) {
            throw new \InvalidArgumentException('Unsupported type');
        }

        $this->type = $type;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function isPostOffice(): bool
    {
        $this->guardAgainstEmptyType();

        return $this->type === self::TYPE_POST_OFFICE;
    }

    public function isTerminal(): bool
    {
        $this->guardAgainstEmptyType();

        return $this->type === self::TYPE_TERMINAL;
    }

    private function guardAgainstEmptyType(): void
    {
        if ($this->type === null) {
            throw new \RuntimeException('No type has been provided');
        }
    }
}
