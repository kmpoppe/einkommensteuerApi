<?php

// include class
require_once(__DIR__ . "/soliValues.php");

class calcSoli {

	private $soliValues = Array();

	function __construct()
	{
		$this->_getSoliValues(null);
	}

	function _getSoliValues($year) {
		if (sizeof($this->soliValues) == 0) {
			$this->soliValues = Array(
				2007 => new soliValues(  972, 0.200),
				2021 => new soliValues(16956, 0.119),
				2022 => new soliValues(16956, 0.119),
				2023 => new soliValues(17543, 0.119),
				2024 => new soliValues(18130, 0.119),
				2025 => new soliValues(19950, 0.119),
				2026 => new soliValues(20350, 0.119)
			);
			// Programmablaufpläne 2007 - 2020 identische Werte
			for ($duplicateY = 2008; $duplicateY <= 2020; $duplicateY++)
				$this->soliValues[$duplicateY] = $this->soliValues[2007];
		}

		// initialization of Array was called
		if (is_null($year))
			return null;

		$k = array_keys($this->soliValues);
		if (!in_array($year, range(min($k), max($k))))
			return null;
		else
			return $this->soliValues[$year];
	}

	// external: calc
	function calc($year, $split, $ESt)
	{
		$soliValues = $this->_getSoliValues($year);

		if (is_null($soliValues)) {
			return Array("result" => "InternalError", "error" => "No calculation values available");
		}

		$solzFrei = $soliValues->solzFrei * ($split ? 2 : 1);

		$Soli = ($ESt > $solzFrei) ? $ESt * $soliValues->solzJ : 0.0;
		$SoliMax = ($ESt > $solzFrei) ? ($ESt - $solzFrei) * $soliValues->solzMinF : 0.0;
		if ($Soli > $SoliMax)
			$Soli = $SoliMax;
		$Soli = floor($Soli * 100) / 100;

		return Array("result" => "OK", "value" => $Soli);
	}

	// external: validYears
	function validYears() {
		$lowYear  = min(array_keys($this->soliValues));
		$highYear = max(array_keys($this->soliValues));
		return Array($lowYear, $highYear);
	}
	
}

?>
