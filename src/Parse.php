<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportType;

class Parse
{
    public static function reportType(string $reportString): ReportType
    {
        if (substr(trim($reportString), 0, 5) === 'SPECI') return ReportType::SPECI;
        return ReportType::METAR;
    }
}
