<?php

namespace Aifrus\METAR\Enums;

enum Cardinal4: string
{
    case North = 'N';
    case East = 'E';
    case South = 'S';
    case West = 'W';
    case Unknown = 'U';

    // Static method to retrieve the direction as a heading
    public static function getHeading(self $direction): float
    {
        return match ($direction) {
            self::North => 0,
            self::East => 90,
            self::South => 180,
            self::West => 270,
            default => -1,
        };
    }

    // Static method to retrieve the direction ranges
    public static function getDirectionRanges(): array
    {
        foreach (self::cases() as $case) {
            $heading = self::getHeading($case);
            $ranges[$case->value] = ['from' => ($heading - 45) % 360, 'to' => ($heading + 45) % 360];
        }
        return $ranges;
    }

    public static function fromValue(int|float|string|null $value): self
    {
        if (is_null($value)) {
            return self::Unknown;
        }

        if (is_numeric($value)) {
            $value = floatval($value);
            foreach (self::getDirectionRanges() as $direction => $range) {
                if ($value >= $range['from'] && $value < $range['to']) {
                    return self::from($direction);
                }
            }
        }

        if (is_string($value) && in_array(strtoupper($value), self::cases())) {
            return self::from($value);
        }

        return self::Unknown;
    }
}
