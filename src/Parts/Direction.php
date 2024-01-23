<?php

namespace Aifrus\METAR\Parts;

use Aifrus\METAR\Enums\{
    Cardinal4,
    Cardinal8,
    Cardinal16
};
use Aifrus\METAR\Exceptions\METARException;

class Direction
{
    public ?int $asInt = null;
    public ?float $asFloat = null;
    public ?string $asString = null;
    public ?Cardinal4 $asCardinal4 = null;
    public ?Cardinal8 $asCardinal8 = null;
    public ?Cardinal16 $asCardinal16 = null;

    public static function create(int|float|string|null $directionInput): self
    {
        return new self($directionInput);
    }

    public function __construct(public int|float|string|null $directionInput)
    {
        $this->validate();
        $this->asInt = (int) $this->directionInput;
        $this->asFloat = (float) $this->directionInput;
        $this->asString = $this->directionInput;
        $this->asCardinal4 = Cardinal4::fromValue($this->asFloat);
        $this->asCardinal8 = Cardinal8::fromValue($this->asFloat);
        $this->asCardinal16 = Cardinal16::fromValue($this->asFloat);
    }

    private function validate(): void
    {
        if (!is_numeric($this->directionInput)) throw new METARException('Direction must be numeric');
        if ($this->directionInput < 0 || $this->directionInput > 360) throw new METARException('Direction must be between 0 and 360');
    }

    public function __toString(): string
    {
        return $this->directionInput;
    }
}
