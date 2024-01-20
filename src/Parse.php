<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportType;

class Parse
{
    public const SPECI_TEXT = 'SPECI';

    public static function reportType(string $reportType): ReportType
    {
        return $reportType === self::SPECI_TEXT ? ReportType::SPECI : ReportType::METAR;
    }
}
