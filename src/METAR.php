<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Timestamp;
use Aifrus\METAR\Enums\ReportType;
use Aifrus\METAR\Exceptions\METARException;
use RPurinton\HTTPS\HTTPSRequest;

class METAR
{
	public ?string $reportString = null;
	public ?ReportType $reportType = null;
	public ?string $stationIdentifier = null;
	public ?Timestamp $timestamp = null;

	public function __construct(string $reportString)
	{
		$this->reportString = trim($reportString);
		$reportParts = explode(' ', $this->reportString);
		$this->reportType = Parse::reportType($reportParts[0]);
		if ($this->reportType === ReportType::SPECI) array_shift($reportParts);
		$this->stationIdentifier = $reportParts[0];
		$this->timestamp = Timestamp::create($reportParts[1]);
	}

	public static function fetch(string $stationIdentifier): METAR
	{
		$reportString = HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/' . $stationIdentifier]);
		if (empty($reportString)) throw new METARException('No METAR data found for ' . $stationIdentifier);
		return new METAR($reportString);
	}
}
