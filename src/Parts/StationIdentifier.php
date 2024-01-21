<?php

namespace Aifrus\METAR\Parts;

use Aifrus\METAR\Exceptions\METARException;

class StationIdentifier
{
    public static function create(string $stationIdentifier): self
    {
        return new self($stationIdentifier);
    }

    public function __construct(public string $stationIdentifier)
    {
        $this->validate();
    }

    private function validate(): void
    {
        if (strlen($this->stationIdentifier) !== 4) throw new METARException('Station identifier must be 4 characters long');
    }

    public function __toString(): string
    {
        return $this->stationIdentifier;
    }
}
