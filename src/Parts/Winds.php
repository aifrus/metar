<?php

namespace Aifrus\METAR\Parts;

use Aifrus\METAR\Parts\Direction;
use Aifrus\METAR\Exceptions\METARException;

class Winds
{
    public const MAX_DIRECTION = 360;
    public const MIN_DIRECTION = 0;
    public const UNKNOWN_WIND = '/////KT';
    public const VARIABLE_WIND = 'VRB';

    public ?string $windString = null;
    public ?bool $variable = null;
    public ?Direction $direction = null;
    public ?int $speed = null;
    public ?int $gust = null;
    public ?bool $variableRange = null;
    public ?Direction $variableFrom = null;
    public ?Direction $variableTo = null;

    public static function create(string $windString, ?string $nextString): Winds
    {
        return new Winds($windString, $nextString);
    }

    public function __construct(string $windString, ?string $nextString)
    {
        $this->windString = $windString;

        $this->parseWindString($windString);
        $this->parseNextString($nextString);

        $this->validate();
    }

    private function parseWindString(string $windString): void
    {
        if (strpos($windString, 'KT') === false) throw new METARException('Wind string must contain KT');
        $windString = str_replace('KT', '', $windString);

        if ($windString === self::UNKNOWN_WIND) {
            $this->variable = false;
            $this->direction = null;
            $this->speed = null;
            return;
        }

        if (substr($windString, 0, 3) === self::VARIABLE_WIND) {
            $this->variable = true;
            $this->direction = null;
            $this->speed = (int) substr($windString, 3);
            return;
        }

        preg_match('/(\d{3})(\d{2,3})/', $windString, $matches);
        $this->direction = Direction::create((int) $matches[1]);
        $this->speed = (int) $matches[2];

        $gustPosition = strpos($windString, 'G');
        if ($gustPosition !== false) {
            preg_match('/G(\d{2,3})/', $windString, $gustMatches);
            $this->gust = (int) $gustMatches[1];
        }
    }

    private function parseNextString(?string $nextString): void
    {
        if (!$nextString || !preg_match('/^(\d{3})V(\d{3})$/', $nextString, $matches)) {
            $this->variableRange = false;
            $this->variableFrom = null;
            $this->variableTo = null;
            return;
        }
        $from = (int) $matches[1];
        $to = (int) $matches[2];
        if ($from < self::MIN_DIRECTION || $from > self::MAX_DIRECTION || $to < self::MIN_DIRECTION || $to > self::MAX_DIRECTION) throw new METARException('Wind variable values must be >= 0 and < 360');
        if ($from === $to) throw new METARException('Wind variable values must be different');
        $this->variableRange = true;
        $this->variableFrom = $from;
        $this->variableTo = $to;
    }

    public function validate(): void
    {
        $this->validateRange($this->direction, self::MIN_DIRECTION, self::MAX_DIRECTION, 'Wind direction must be between 0 and 360');
        $this->validateRange($this->speed, self::MIN_DIRECTION, PHP_INT_MAX, 'Wind speed must be a positive integer');
        $this->validateRange($this->gust, self::MIN_DIRECTION, PHP_INT_MAX, 'Wind gust must be a positive integer');
        $this->validateRange($this->variableFrom, self::MIN_DIRECTION, self::MAX_DIRECTION, 'Wind variable from must be between 0 and 360');
        $this->validateRange($this->variableTo, self::MIN_DIRECTION, self::MAX_DIRECTION, 'Wind variable to must be between 0 and 360');
    }

    private function validateRange(?int $value, int $min, int $max, string $errorMessage): void
    {
        if ($value !== null && ($value < $min || $value > $max)) {
            throw new METARException($errorMessage);
        }
    }

    public function __toString(): string
    {
        if ($this->variable) {
            $windString = self::VARIABLE_WIND;
        } else {
            $windString = str_pad((string) $this->direction, 3, '0', STR_PAD_LEFT);
        }
        $windString .= str_pad((string) $this->speed, 2, '0', STR_PAD_LEFT);
        if ($this->gust) {
            $windString .= 'G' . str_pad((string) $this->gust, 2, '0', STR_PAD_LEFT);
        }
        if ($this->variableRange) {
            $windString .= " " . str_pad((string) $this->variableFrom, 3, '0', STR_PAD_LEFT) . 'V' . str_pad((string) $this->variableTo, 3, '0', STR_PAD_LEFT);
        }
        $windString .= 'KT';
        return $windString;
    }
}
