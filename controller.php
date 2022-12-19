<?php

require_once(__DIR__ . "/calcESt.php");
require_once(__DIR__ . "/calcSoli.php");

set_time_limit(0);
header('Content-type: application/json');

$errorStack = Array();

if (!array_key_exists("REQUEST_METHOD", $_SERVER)) {
	// console execution
	$calledMethod = $_SERVER["argv"][1];
	$postData     = $_SERVER["argv"][2];
} else {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$calledMethod = $_GET["method"];
		$postData     = file_get_contents("php://input");
		try {
			$data = json_decode($postData, false, 512, JSON_THROW_ON_ERROR);
		} catch (JsonException $e) {
			$errorStack[] = "JSON decode error";
		}
	} else {
		$calledMethod = $_GET["method"];
	}
}

if (sizeof($errorStack) == 0) {
	// Wenn splitting nicht im Eingangs-Datenfeld ist, nehmen wir false an. TRUE, 1 oder "true" setzen true.
	if (property_exists($data, "splitting")) {
		$splitting = ($data->splitting === true || $data->splitting == "1" || strtolower($data->splitting) == "true" ? true : false);
	} else {
		$splitting = false;
	}
	switch ($calledMethod) {
		case "calcESt": {
			$calcObj = new calcESt();
			// Fehlerbehandlung
			if (!property_exists($data, "year")) {
				$errorStack[] = "No 'year' in input data";
			} else {
				$validYears = $calcObj->validYears();
				if ($data->year < $validYears[0] || $data->year > $validYears[1]) {
					$errorStack[] = "'year' outside supported range";
				}
			}
			if (!property_exists($data, "zvE")) {
				$errorStack[] = "No 'zvE' in input data";
			}
			// keine Fehler, Berechnung ausführen
			if (sizeof($errorStack) == 0) {
				$returnData = $calcObj->calc(intval($data->year), $splitting, intval($data->zvE));
			}
			break;
		}
		case "calcSoli": {
			$calcObj = new calcSoli();
			// Fehlerbehandlung
			if (!property_exists($data, "year")) {
				$errorStack[] = "No 'year' in input data";
			} else {
				$validYears = $calcObj->validYears();
				if ($data->year < $validYears[0] || $data->year > $validYears[1]) {
					$errorStack[] = "'year' outside supported range";
				}
			}
			if (!property_exists($data, "ESt")) {
				$errorStack[] = "No 'ESt' in input data";
			}
			// keine Fehler, Berechnung ausführen
			if (sizeof($errorStack) == 0) {
				$returnData = $calcObj->calc(intval($data->year), $splitting, intval($data->ESt));
			}
			break;
		}
		case "validYearsESt": {
			$calcObj = new calcESt();
			$validYears = $calcObj->validYears();
			$returnData = Array("minimum" => $validYears[0], "maximum" => $validYears[1]);
			break;
		}
		case "validYearsSoli": {
			$calcObj = new calcESt();
			$validYears = $calcObj->validYears();
			$returnData = Array("minimum" => $validYears[0], "maximum" => $validYears[1]);
			break;
		}
		default: {
			$errorStack[] = "No supported method supplied";
			break;
		}
	}
}

if (sizeof($errorStack) > 0) {
	$returnData = Array("result" => "Error", "errors" => implode("\r\n", $errorStack));
	$returnData["calledMethod"] = $calledMethod;
} else {
	$returnData["calledMethod"] = $calledMethod;
}

echo json_encode($returnData);
?>