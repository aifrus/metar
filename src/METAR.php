<?php

namespace Aifrus\METAR;

use Aifrus\METAR\Enums\ReportType;
use Aifrus\METAR\Exceptions\METARException;
use RPurinton\HTTPS\HTTPSRequest;

class METAR
{
	public ?ReportType $reportType = null;

	public function __construct(public string $reportString)
	{
		$this->reportType = Parse::reportType($this->reportString);
	}

	public static function fetch(string $stationIdentifier): METAR
	{
		$reportString = HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/' . $stationIdentifier]);
		if (empty($reportString)) throw new METARException('No METAR data found for ' . $stationIdentifier);
		return new METAR($reportString);
	}
}
