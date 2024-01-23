<?php

namespace Aifrus\METAR\Enums;

enum Cardinal16: string
{
    case North = 'N';
    case NorthNorthEast = 'NNE';
    case NorthEast = 'NE';
    case EastNorthEast = 'ENE';
    case East = 'E';
    case EastSouthEast = 'ESE';
    case SouthEast = 'SE';
    case SouthSouthEast = 'SSE';
    case South = 'S';
    case SouthSouthWest = 'SSW';
    case SouthWest = 'SW';
    case WestSouthWest = 'WSW';
    case West = 'W';
    case WestNorthWest = 'WNW';
    case NorthWest = 'NW';
    case NorthNorthWest = 'NNW';
    case Unknown = 'U';

    // Static method to retrieve the direction as a heading
    public static function getHeading(self $direction): float
    {
        return match ($direction) {
            self::North => 0,
            self::NorthNorthEast => 22.5,
            self::NorthEast => 45,
            self::EastNorthEast => 67.5,
            self::East => 90,
            self::EastSouthEast => 112.5,
            self::SouthEast => 135,
            self::SouthSouthEast => 157.5,
            self::South => 180,
            self::SouthSouthWest => 202.5,
            self::SouthWest => 225,
            self::WestSouthWest => 247.5,
            self::West => 270,
            self::WestNorthWest => 292.5,
            self::NorthWest => 315,
            self::NorthNorthWest => 337.5,
            default => -1,
        };
    }

    // Static method to retrieve the direction ranges
    public static function getDirectionRanges(): array
    {
        foreach (self::cases() as $case) {
            $heading = self::getHeading($case);
            $ranges[$case->value] = ['from' => ($heading - 11.25) % 360, 'to' => ($heading + 11.25) % 360];
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
