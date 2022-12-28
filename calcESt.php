<?php

// include classes
include(__DIR__ . "/estZoneLimits.php");
include(__DIR__ . "/estFormulaValues.php");

class calcESt {

	private $zoneLimits    = Array();
	private $formulaValues = Array();

	function __construct()
	{
		$this->_getZoneLimits(null);
		$this->_getFormulaValues(null);
	}

	// internal: preparation of values
	function _getZoneLimits($year)
	{
		if (sizeof($this->zoneLimits) == 0) {
			$this->zoneLimits = Array(
				2007 => new estZoneLimits( 7664, 12739, 52151, 250000),
				2008 => new estZoneLimits( 7664, 12739, 52151, 250000),
				2009 => new estZoneLimits( 7834, 13139, 52551, 250400),
				2010 => new estZoneLimits( 8004, 13469, 52881, 250730),
				2011 => new estZoneLimits( 8004, 13469, 52881, 250730),
				2012 => new estZoneLimits( 8004, 13469, 52881, 250730),
				2013 => new estZoneLimits( 8130, 13469, 52881, 250730),
				2014 => new estZoneLimits( 8354, 13469, 52881, 250730),
				2015 => new estZoneLimits( 8472, 13469, 52881, 250730),
				2016 => new estZoneLimits( 8652, 13669, 53665, 254446),
				2017 => new estZoneLimits( 8820, 13769, 54057, 256303),
				2018 => new estZoneLimits( 9000, 13996, 54949, 260532),
				2019 => new estZoneLimits( 9168, 14254, 55960, 265326),
				2020 => new estZoneLimits( 9408, 14532, 57051, 270500),
				2021 => new estZoneLimits( 9744, 14753, 57918, 274612),
				2022 => new estZoneLimits(10346, 14926, 58596, 277825),
				2023 => new estZoneLimits(10908, 15999, 62809, 277825),
				2024 => new estZoneLimits(11604, 17005, 66760, 277825)
			);
		}

		// initialization of Array was called
		if (is_null($year))
			return null;

		$k = array_keys($this->zoneLimits);
		if (!in_array($year, range(min($k), max($k))))
			return null;
		else
			return $this->zoneLimits[$year];
	}

	function _getFormulaValues($year) {
		if (sizeof($this->formulaValues) == 0) {
			$this->formulaValues = Array(
				2007 => new estFormulaValues( 883.74, 228.74,  989.00,  7914.00, 15414.00),
				2008 => new estFormulaValues( 883.74, 228.74,  989.00,  7914.00, 15414.00),
				2009 => new estFormulaValues( 939.68, 228.74, 1007.00,  8064.00, 15576.00),
				2010 => new estFormulaValues( 912.17, 228.74, 1038.00,  8172.00, 15694.00),
				2011 => new estFormulaValues( 912.17, 228.74, 1038.00,  8172.00, 15694.00),
				2012 => new estFormulaValues( 912.17, 228.74, 1038.00,  8172.00, 15694.00),
				2013 => new estFormulaValues( 933.70, 228.74, 1014.00,  8196.00, 15718.00),
				2014 => new estFormulaValues( 974.58, 228.74,  971.00,  8239.00, 15761.00),
				2015 => new estFormulaValues( 997.60, 228.74,  948.68,  8261.29, 15783.19),
				2016 => new estFormulaValues( 993.62, 225.40,  952.48,  8394.14, 16027.52),
				2017 => new estFormulaValues(1007.27, 223.76,  939.57,  8475.44, 16164.53),
				2018 => new estFormulaValues( 997.80, 220.13,  948.49,  8621.75, 16437.70),
				2019 => new estFormulaValues( 980.14, 216.16,  965.58,  8780.90, 16740.68),
				2020 => new estFormulaValues( 972.87, 212.02,  972.79,  8963.74, 17078.74),
				2021 => new estFormulaValues( 995.21, 208.85,  950.96,  9136.63, 17374.99),
				2022 => new estFormulaValues(1088.67, 206.43,  869.32,  9336.45, 17671.20),
				2023 => new estFormulaValues( 979.18, 192.59,  966.53,  9972.28, 18307.73),
				2024 => new estFormulaValues( 922.98, 181.19, 1025.38, 10602.13, 18936.88)
			);
		}

		// initialization of Array was called
		if (is_null($year))
			return null;

		$k = array_keys($this->formulaValues);
		if (!in_array($year, range(min($k), max($k))))
			return null;
		else
			return $this->formulaValues[$year];
	}

	// external: calcESt
	function calc($year, $split, $zvE)
	{	
		// Splittingverfahren: Wenn Zusammenveranlagung, wird die ESt auf die Hälfte des gemeinsamen Einkommens berechnet
		$zvE *= ($split ? 0.5 : 1);

		$ESt = 0.0;
		
		$zoneLimits    = $this->_getZoneLimits($year);    // Zonenlimits
		$formulaValues = $this->_getFormulaValues($year); // Formelbestandteile

		if (is_null($zoneLimits) || is_null($formulaValues)) {
			return Array("result" => "InternalError", "error" => "No calculation values available");
		}

		if ($zvE < $zoneLimits->zone0 + 1)
		{		
			// Grundfreibetrag => keine ESt zu zahlen
			$ESt = 0.0;
		}
		elseif ($zvE < $zoneLimits->zone1 + 1)
		{
			// Untere Progressionszone
			$y = ($zvE - $zoneLimits->zone0) / 10000.0;
			// Eingangssteuersatz 15.0% für 2007 und 2008, danach 14.0%
			$startEStPerc = (in_array($year, range(2007, 2008)) ? 1500 : 1400);
			$ESt = ($formulaValues->zone1factor * $y + $startEStPerc) * $y;
		}
		elseif ($zvE < $zoneLimits->zone2 + 1)
		{
			// Obere Progressionszone
			$y = ($zvE - $zoneLimits->zone1) / 10000.0;
			$ESt = ($formulaValues->zone2factor * $y + 2397) * $y + $formulaValues->zone2addition;
		}
		elseif ($zvE < $zoneLimits->zone3 + 1)
		{
			// 1. Proportionalzone
			$ESt = 0.42 * $zvE - $formulaValues->zone3deduction;
		}
		else
		{
			// 2. Proportionalzone
			$ESt = 0.45 * $zvE - $formulaValues->zone4deduction;
		}
		
		// Vorgabe des Bundes seit 2004: Die Berechnung erfolgt OHNE Rundung direkt mit Gleitkommawerten.
		// Das Ergebnis ist auf volle Euro ABzurunden. 
		// Daher gib die ESt als int (nicht float) zurück.
		// Hier auch Verdopplung des Betrags falls Zusammenveranlagung
		
		$ESt = intval($ESt, 10) * ($split ? 2 : 1);
		
		return Array("result" => "OK", "value" => $ESt);

	}

	// external: validYears
	function validYears() {
		$lowYear  = max(min(array_keys($this->formulaValues)), min(array_keys($this->zoneLimits)));
		$highYear = min(max(array_keys($this->formulaValues)), max(array_keys($this->zoneLimits)));
		return Array($lowYear, $highYear);
	}

	// external: getGrundfreibetrag
	function getGrundfreibetrag($year) {
		$tzl = $this->_getZoneLimits($year);
		return Array("result" => "OK", "value" => $tzl->zone0);
	}

}
?>