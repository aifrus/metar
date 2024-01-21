<?php

namespace Aifrus\METAR\Parts;

use Aifrus\METAR\Enums\ReportModifierEnum;

class ReportModifier
{
    public ?ReportModifierEnum $reportModifier = null;

    public static function create(string $reportModifier): self
    {
        return new self($reportModifier);
    }

    public function __construct(string $reportModifier)
    {
        $this->reportModifier = ReportModifierEnum::create($reportModifier);
    }

    public function __toString(): string
    {
        return (string)$this->reportModifier;
    }
}
