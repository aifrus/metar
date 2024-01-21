<?php

namespace Aifrus\METAR\Parts;

use Aifrus\METAR\Enums\ReportTypeEnum;

class ReportType
{
    public ?ReportTypeEnum $reportType = null;

    public static function create(string $reportType): self
    {
        return new self($reportType);
    }

    public function __construct(string $reportType)
    {
        $this->reportType = ReportTypeEnum::create($reportType);
    }

    public function __toString(): string
    {
        return (string)$this->reportType;
    }
}
