<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Exceptions\METARException;

class Timestamp
{
    public ?string $timestampString = null;
    public ?int $day = null;
    public ?int $hour = null;
    public ?int $minute = null;

    public function __construct(string $timestampString)
    {
        $this->timestampString = $timestampString;
        $this->day = (int) substr($timestampString, 0, 2);
        $this->hour = (int) substr($timestampString, 2, 2);
        $this->minute = (int) substr($timestampString, 4, 2);
    }

    public static function create(string $timestampString): Timestamp
    {
        return new Timestamp($timestampString);
    }

    public function __toString(): string
    {
        return $this->timestampString;
    }
}
