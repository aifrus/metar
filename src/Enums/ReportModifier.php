<?php

namespace Aifrus\METAR\Enums;

enum ReportModifier: string
{
    case NONE = '';
    case AUTO = 'AUTO';
    case COR = 'COR';
    case NIL = 'NIL';

    public static function create(string $reportModifier): ReportModifier
    {
        return match ($reportModifier) {
            'AUTO' => ReportModifier::AUTO,
            'COR' => ReportModifier::COR,
            'NIL' => ReportModifier::NIL,
            default => ReportModifier::NONE
        };
    }
}
