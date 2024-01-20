<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Exceptions\METARException;

class Winds
{
    public ?string $windString = null;
    public ?int $direction = null;
    public ?int $speed = null;
    public ?int $gust = null;
    public ?int $variationFrom = null;
    public ?int $variationTo = null;
    public ?int $variationDirection = null;

    public function __construct(string $windString)
    {
        $this->windString = $windString;
        $this->direction = (int) substr($windString, 0, 3);
        $this->speed = (int) substr($windString, 3, 2);
        if (strlen($windString) >= 7) $this->gust = (int) substr($windString, 5, 2);
        if (strlen($windString) >= 10) $this->variationFrom = (int) substr($windString, 7, 3);
        if (strlen($windString) >= 13) $this->variationTo = (int) substr($windString, 10, 3);
        if (strlen($windString) >= 16) $this->variationDirection = (int) substr($windString, 13, 3);
        $this->validate();
    }

    public function validate(): void
    {
        if ($this->direction < 0 || $this->direction > 360) throw new METARException('Invalid wind direction');
        if ($this->speed < 0 || $this->speed > 99) throw new METARException('Invalid wind speed');

        if ($this->gust !== null && ($this->gust < 0 || $this->gust > 99)) throw new METARException('Invalid wind gust');
        if ($this->variationFrom !== null && ($this->variationFrom < 0 || $this->variationFrom > 360)) throw new METARException('Invalid wind variation from');
        if ($this->variationTo !== null && ($this->variationTo < 0 || $this->variationTo > 360)) throw new METARException('Invalid wind variation to');
        if ($this->variationDirection !== null && ($this->variationDirection < 0 || $this->variationDirection > 360)) throw new METARException('Invalid wind variation direction');
    }

    public static function create(string $windString): Winds
    {
        return new Winds($windString);
    }

    public function __toString(): string
    {
        return $this->windString;
    }
}
