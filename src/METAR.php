<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportModifierEnum;
use Aifrus\METAR\Parts\ReportType;
use Aifrus\METAR\Parts\StationIdentifier;
use Aifrus\METAR\Parts\Timestamp;
use Aifrus\METAR\Parts\ReportModifier;
use Aifrus\METAR\Parts\Winds;
use Aifrus\METAR\Exceptions\METARException;

class METAR
{
	public ?string $reportString = null;
	public ?ReportType $reportType = null;
	public ?StationIdentifier $stationIdentifier = null;
	public ?Timestamp $timeStamp = null;
	public ?ReportModifier $reportModifier = null;
	public ?Winds $winds = null;

	public static function create(string $reportString): METAR
	{
		return new METAR($reportString);
	}

	public function __construct(string $reportString)
	{
		$this->reportString = trim($reportString);
		$reportParts = explode(' ', $this->reportString);
		$this->reportType = ReportType::create($reportParts[0]);
		if (in_array($reportParts[0], ['METAR', 'SPECI'])) array_shift($reportParts);
		if (ReportModifierEnum::isA($reportParts[0])) $this->reportModifier = ReportModifier::create(array_shift($reportParts));
		$this->stationIdentifier = StationIdentifier::create(array_shift($reportParts));
		if (ReportModifierEnum::isA($reportParts[0])) $this->reportModifier = ReportModifier::create(array_shift($reportParts));
		$this->timeStamp = Timestamp::create(array_shift($reportParts));
		if (ReportModifierEnum::isA($reportParts[0])) $this->reportModifier = ReportModifier::create(array_shift($reportParts));
		if (!$this->reportModifier) $this->reportModifier = ReportModifier::create('NONE');
		$this->winds = Winds::create(array_shift($reportParts), $reportParts[0] ?? null);
		if ($this->winds->variableFrom) array_shift($reportParts);
	}

	public function __toString(): string
	{
		return $this->reportString;
	}
}
