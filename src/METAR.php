<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportModifierEnum;
use Aifrus\METAR\Parts\ReportType;
use Aifrus\METAR\Parts\StationIdentifier;
use Aifrus\METAR\Parts\Timestamp;
use Aifrus\METAR\Parts\ReportModifiers;
use Aifrus\METAR\Parts\Winds;

class METAR
{
	public ?string $reportString = null;
	public ?ReportType $reportType = null;
	public ?StationIdentifier $stationIdentifier = null;
	public ?Timestamp $timeStamp = null;
	public ?ReportModifiers $reportModifiers = null;
	public ?Winds $winds = null;
	public ?bool $requiresMaintenance = null;

	public static function create(string $reportString): self
	{
		return (new self)->fill($reportString);
	}

	public function fill(string $reportString): self
	{
		$this->reportString = trim($reportString);
		$reportParts = explode(' ', $this->reportString);
		$this->reportModifiers = ReportModifiers::create($reportParts);
		$this->reportType = ReportType::create($reportParts[0]);
		if (in_array($reportParts[0], ['METAR', 'SPECI'])) array_shift($reportParts);
		$this->stationIdentifier = StationIdentifier::create(array_shift($reportParts));
		$this->timeStamp = Timestamp::create(array_shift($reportParts));
		$this->winds = Winds::create(array_shift($reportParts), $reportParts[0] ?? null);
		if ($this->winds->variableRange) array_shift($reportParts);
		$this->requiresMaintenance = substr($this->reportString, -1) === '$';
		return $this;
	}

	public function __toString(): string
	{
		return $this->reportString;
	}
}
