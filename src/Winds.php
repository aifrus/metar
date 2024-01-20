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

    public function __construct(string $windString)
    {
        $this->windString = $windString;

        if ($windString === '00000KT') {
            $this->direction = 0;
            $this->speed = 0;
            return;
        }

        if ($windString === '/////KT') {
            return;
        }

        if (substr($windString, 0, 3) === 'VRB') {
            $this->direction = null;
            $this->speed = (int) substr($windString, 3, 2);
            return;
        }

        $this->direction = (int) substr($windString, 0, 3);
        $this->speed = (int) substr($windString, 3, 2);

        $gustPosition = strpos($windString, 'G');
        if ($gustPosition !== false) {
            $this->gust = (int) substr($windString, $gustPosition + 1, 2);
        }

        $variationPosition = strpos($windString, 'V');
        if ($variationPosition !== false) {
            $this->variationFrom = (int) substr($windString, $variationPosition - 3, 3);
            $this->variationTo = (int) substr($windString, $variationPosition + 1, 3);
        }

        $this->validate();
    }

    public function validate(): void
    {
        if ($this->direction !== null && ($this->direction < 0 || $this->direction > 360)) throw new METARException('Invalid wind direction');
        if ($this->speed !== null && $this->speed < 0) throw new METARException('Invalid wind speed');
        if ($this->gust !== null && ($this->gust < 0 || ($this->gust - $this->speed) < 10)) throw new METARException('Invalid wind gust');
        if ($this->variationFrom !== null && ($this->variationFrom < 0 || $this->variationFrom > 360)) throw new METARException('Invalid wind variation from');
        if ($this->variationTo !== null && ($this->variationTo < 0 || $this->variationTo > 360)) throw new METARException('Invalid wind variation to');
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
