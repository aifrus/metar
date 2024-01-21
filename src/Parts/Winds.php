<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Exceptions\METARException;

class Winds
{
    public const MAX_DIRECTION = 360;
    public const MIN_DIRECTION = 0;
    public const NO_WIND = '00000KT';
    public const UNKNOWN_WIND = '/////KT';
    public const VARIABLE_WIND = 'VRB';

    public ?string $windString = null;
    public ?int $direction = null;
    public ?int $speed = null;
    public ?int $gust = null;
    public ?int $variationFrom = null;
    public ?int $variationTo = null;

    public function __construct(string $windString, ?string $nextString)
    {
        $this->windString = $windString;

        $this->parseWindString($windString);
        $this->parseNextString($nextString);

        $this->validate();
    }

    private function parseWindString(string $windString): void
    {
        if ($windString === self::NO_WIND) {
            $this->direction = self::MIN_DIRECTION;
            $this->speed = self::MIN_DIRECTION;
            return;
        }

        if ($windString === self::UNKNOWN_WIND) {
            return;
        }

        if (substr($windString, 0, 3) === self::VARIABLE_WIND) {
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
    }

    private function parseNextString(?string $nextString): void
    {
        if (!$nextString || !preg_match('/^(\d{3})V(\d{3})$/', $nextString, $matches)) return;
        $from = (int) $matches[1];
        $to = (int) $matches[2];
        if ($from < self::MIN_DIRECTION || $from > self::MAX_DIRECTION || $to < self::MIN_DIRECTION || $to > self::MAX_DIRECTION) throw new METARException('Wind variation values must be between 0 and 360');
        $this->variationFrom = $from;
        $this->variationTo = $to;
    }

    public function validate(): void
    {
        $this->validateRange($this->direction, self::MIN_DIRECTION, self::MAX_DIRECTION, 'Wind direction must be between 0 and 360');
        $this->validateRange($this->speed, self::MIN_DIRECTION, PHP_INT_MAX, 'Wind speed must be a positive integer');
        $this->validateRange($this->gust, self::MIN_DIRECTION, PHP_INT_MAX, 'Wind gust must be a positive integer');
        $this->validateRange($this->variationFrom, self::MIN_DIRECTION, self::MAX_DIRECTION, 'Wind variation from must be between 0 and 360');
        $this->validateRange($this->variationTo, self::MIN_DIRECTION, self::MAX_DIRECTION, 'Wind variation to must be between 0 and 360');
    }

    private function validateRange(?int $value, int $min, int $max, string $errorMessage): void
    {
        if ($value !== null && ($value < $min || $value > $max)) {
            throw new METARException($errorMessage);
        }
    }

    public static function create(string $windString, string $nextString): Winds
    {
        return new Winds($windString, $nextString);
    }

    public function __toString(): string
    {
        return $this->windString;
    }
}
