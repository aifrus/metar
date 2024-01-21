<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportTypeEnum;
use Aifrus\METAR\Enums\ReportModifierEnum;
use Aifrus\METAR\Parts\ReportType;
use Aifrus\METAR\Parts\StationIdentifier;
use Aifrus\METAR\Parts\Timestamp;
use Aifrus\METAR\Parts\ReportModifier;
use Aifrus\METAR\Parts\Winds;
use Aifrus\METAR\Exceptions\METARException;
use RPurinton\HTTPS\HTTPSRequest;

class METAR
{
	public ?string $reportString = null;
	public ?ReportType $reportType = null;
	public ?StationIdentifier $stationIdentifier = null;
	public ?Timestamp $timestamp = null;
	public ?ReportModifier $reportModifier = null;
	public ?Winds $winds = null;

	public function __construct(string $reportString)
	{
		$this->reportString = trim($reportString);
		$reportParts = explode(' ', $this->reportString);

		$this->reportType = ReportType::create($reportParts[0]);
		if ($this->reportType->reportType === ReportTypeEnum::SPECI) array_shift($reportParts);

		$this->stationIdentifier = StationIdentifier::create(array_shift($reportParts));

		$this->timestamp = Timestamp::create(array_shift($reportParts));

		$this->reportModifier = ReportModifier::create($reportParts[0]);
		if ($this->reportModifier->reportModifier !== ReportModifierEnum::NONE) array_shift($reportParts);

		$this->winds = Winds::create(array_shift($reportParts), $reportParts[0] ?? null);
		if ($this->winds->variableFrom) array_shift($reportParts);
	}

	public static function fetch(string $stationIdentifier): METAR
	{
		$reportString = HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/' . $stationIdentifier]);
		if (empty($reportString)) throw new METARException('No METAR data found for ' . $stationIdentifier);
		return new METAR($reportString);
	}

	public function __toString(): string
	{
		return $this->reportString;
	}
}
