<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportType;

class Parse
{
    public static function reportType(array $reportParts): ReportType
    {
        return $reportParts[0] === 'SPECI' ? ReportType::SPECI : ReportType::METAR;
    }

    public static function stationIdentifier(array $reportParts): string
    {
        return $reportParts[0];
    }
}
