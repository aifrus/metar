<?php

namespace Aifrus\METAR\Parts;

use Aifrus\METAR\Exceptions\METARException;

class Timestamp
{
    public ?string $timestampString = null;
    public ?int $day = null;
    public ?int $hour = null;
    public ?int $minute = null;

    public static function create(string $timestampString): self
    {
        return new self($timestampString);
    }

    public function __construct(string $timestampString)
    {
        $this->timestampString = $timestampString;
        $this->day = (int) substr($timestampString, 0, 2);
        $this->hour = (int) substr($timestampString, 2, 2);
        $this->minute = (int) substr($timestampString, 4, 2);
        $this->validate();
    }

    private function validate(): void
    {
        if ($this->day < 1 || $this->day > 31) throw new METARException('Invalid day');
        if ($this->hour < 0 || $this->hour > 23) throw new METARException('Invalid hour');
        if ($this->minute < 0 || $this->minute > 59) throw new METARException('Invalid minute');
    }

    public function __toString(): string
    {
        return $this->timestampString;
    }
}
