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

    public function __construct(int|float|string|null $directionInput)
    {
        $this->validate($directionInput);
        $this->asInt = (int) $directionInput;
        $this->asFloat = (float) $directionInput;
        $this->asString = (string) $directionInput;
        $this->asCardinal4 = Cardinal4::fromValue($this->asFloat);
        $this->asCardinal8 = Cardinal8::fromValue($this->asFloat);
        $this->asCardinal16 = Cardinal16::fromValue($this->asFloat);
    }

    private function validate(int|float|string|null $directionInput): void
    {
        if (!is_numeric($directionInput)) throw new METARException('Direction must be numeric');
        if ($directionInput < 0 || $directionInput > 360) throw new METARException('Direction must be greater than or equal to 0 and less than 360');
    }

    public function __toString(): string
    {
        return $this->asString;
    }
}
