<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportModifier;
use Aifrus\METAR\Timestamp;
use Aifrus\METAR\Winds;
use Aifrus\METAR\Enums\ReportType;
use Aifrus\METAR\Exceptions\METARException;
use RPurinton\HTTPS\HTTPSRequest;

class METAR
{
	public ?string $reportString = null;
	public ?ReportType $reportType = null;
	public ?string $stationIdentifier = null;
	public ?Timestamp $timestamp = null;
	public ?ReportModifier $reportModifier = null;
	public ?Winds $winds = null;

	public function __construct(string $reportString)
	{
		$this->reportString = trim($reportString);
		$reportParts = explode(' ', $this->reportString);
		$this->reportType = ReportType::create($reportParts[0]);
		if ($this->reportType === ReportType::SPECI) array_shift($reportParts);
		$this->stationIdentifier = array_shift($reportParts);
		$this->timestamp = Timestamp::create(array_shift($reportParts));
		$this->reportModifier = ReportModifier::create($reportParts[0]);
		if ($this->reportModifier !== ReportModifier::NONE) array_shift($reportParts);
		$this->winds = Winds::create(array_shift($reportParts));
	}

	public static function fetch(string $stationIdentifier): METAR
	{
		$reportString = HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/' . $stationIdentifier]);
		if (empty($reportString)) throw new METARException('No METAR data found for ' . $stationIdentifier);
		return new METAR($reportString);
	}
}
