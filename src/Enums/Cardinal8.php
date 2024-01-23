<?php

namespace Aifrus\METAR\Enums;

enum Cardinal8: string
{
    case North = 'N';
    case NorthEast = 'NE';
    case East = 'E';
    case SouthEast = 'SE';
    case South = 'S';
    case SouthWest = 'SW';
    case West = 'W';
    case NorthWest = 'NW';
    case Unknown = 'U';

    // Static method to retrieve the direction as a heading
    public static function getHeading(self $direction): float
    {
        return match ($direction) {
            self::North => 0,
            self::NorthEast => 45,
            self::East => 90,
            self::SouthEast => 135,
            self::South => 180,
            self::SouthWest => 225,
            self::West => 270,
            self::NorthWest => 315,
            default => -1,
        };
    }

    // Static method to retrieve the direction ranges
    public static function getDirectionRanges(): array
    {
        foreach (self::cases() as $case) {
            $heading = self::getHeading($case);
            $ranges[$case->name] = ['from' => ($heading - 22.5) % 360, 'to' => ($heading + 22.5) % 360];
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
