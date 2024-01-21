<?php

namespace Aifrus\METAR\Enums;

enum ReportTypeEnum: string
{
    case METAR = 'METAR';
    case SPECI = 'SPECI';

    public static function create(string $reportType): self
    {
        return match ($reportType) {
            'SPECI' => self::SPECI,
            default => self::METAR
        };
    }
}
