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

    public ?bool $variable = null;
    public ?Direction $direction = null;
    public ?int $speed = null;
    public ?int $gust = null;
    public ?bool $variableRange = null;
    public ?Direction $variableFrom = null;
    public ?Direction $variableTo = null;

    public static function create(string $windString, ?string $nextString = null): Winds
    {
        return new Winds($windString, $nextString);
    }

    public function __construct(string $windString, ?string $nextString)
    {
        $this->parseWindString($windString);
        $this->parseNextString($nextString);
    }

    private function parseWindString(string $windString): void
    {
        if (substr($windString, -2) !== 'KT') throw new METARException('Wind string must end with KT');
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
        $this->variableFrom = Direction::create($from);
        $this->variableTo = Direction::create($to);
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

    public function crossWinds(int $heading)
    {
        $direction = $this->variableRange ? ($this->variableFrom->asFloat + $this->variableTo->asFloat) / 2 : $this->direction->asFloat;
        $speed = $this->gust ?? $this->speed;

        $angle = ($heading - $direction + 360) % 360;

        $headwind = $speed * cos(deg2rad($angle));
        $crosswind = $speed * sin(deg2rad($angle));

        $tolerance = 1e-10;
        if (abs($crosswind) < $tolerance) {
            $crosswind = 0;
        }

        return [
            'head' => $headwind,
            'right' => $crosswind * -1,
            'left' => $crosswind,
            'tail' => $headwind * -1,
        ];
    }
}
