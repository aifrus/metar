<?php

namespace Aifrus\METAR;

use RPurinton\HTTPS\HTTPSRequest;

class METAR
{
	public static function fetch(string $ids): string
	{
		return HTTPSRequest::fetch(['url' => 'https://metar.vatsim.net/' . $ids]);
	}
}
