<?php

namespace Aifrus\METAR\Enums;

use Aifrus\METAR\Exceptions\METARException;

enum ReportType: string
{
    case METAR = 'METAR';
    case SPECI = 'SPECI';

    public static function create(string $reportType): ReportType
    {
        return match ($reportType) {
            'SPECI' => ReportType::SPECI,
            default => ReportType::METAR
        };
    }
}
