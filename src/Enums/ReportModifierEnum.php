<?php

namespace Aifrus\METAR\Enums;

enum ReportModifierEnum: string
{
    case NONE = '';
    case AUTO = 'AUTO';
    case COR = 'COR';
    case NIL = 'NIL';

    public static function create(string $reportModifier): self
    {
        return match ($reportModifier) {
            'AUTO' => self::AUTO,
            'COR' => self::COR,
            'NIL' => self::NIL,
            default => self::NONE
        };
    }

    public static function isA(string $reportModifier): bool
    {
        return in_array($reportModifier, self::cases());
    }
}
