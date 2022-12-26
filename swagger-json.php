<?php
$fgcOptions = Array("ssl" => Array("verify_peer" => false, "verify_peer_name" => false));  
$validYearsESt  = json_decode(
	file_get_contents("https://" . $_SERVER["HTTP_HOST"]. "/methods/validYearsESt",  false, stream_context_create($fgcOptions)));
$validYearsSoli = json_decode(
	file_get_contents("https://" . $_SERVER["HTTP_HOST"]. "/methods/validYearsSoli", false, stream_context_create($fgcOptions)));
$demoValues     = json_decode(
	file_get_contents("https://" . $_SERVER["HTTP_HOST"]. "/methods/getDemoValues",  false, stream_context_create($fgcOptions)));
?>
{
	"swagger": "2.0",
	"info": {
		"version": "1.0.0",
		"title": "Einkommensteuer API",
		"license": {
			"name": "MIT"
		}
	},
	"host": "einkommensteuerapi.de",
	"basePath": "/",
	"schemes": [
		"https"
	],
	"consumes": [
		"application/json"
	],
	"produces": [
		"application/json"
	],
	"paths": {
		"/methods/calcESt": {
			"post": {
				"summary": "Berechnung der Jahreseinkommensteuer",
				"parameters": [
					{
						"in": "body",
						"name": "body",
						"description": "Angaben zur Berechnung",
						"schema": {
							"type": "object",
							"required": [
								"year",
								"splitting",
								"zvE"
							],
							"properties": {
								"year": {
									"type": "integer",
									"description": "Jahr, für das die Berechnung erfolgen soll",
<?php
echo str_repeat("\t", 9) . "\"minimum\":" . $validYearsESt->minimum . ",\n";
echo str_repeat("\t", 9) . "\"maximum\":" . $validYearsESt->maximum . ",\n";
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->year . "\n";
?>
								},
								"splitting": {
									"type": "boolean",
									"description": "Angabe, ob das Splittingverfahren für zusammenveranlagte steuerpflichtige Personen verwendet werden soll",
<?php
echo str_repeat("\t", 9) . "\"example\":" . ($demoValues->splitting ? "true" : "false") . "\n";
?>
								},
								"zvE": {
									"type": "integer",
									"description": "Zu versteuerndes Einkommen",
									"minimum": 0,
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->zvE . "\n";
?>
								}
							}
						}
					}
				],
				"responses": {
					"200": {
						"description": "Ergebnis der Berechnung",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"calcESt"
									],
									"example": "calcESt"
								},
								"result": {
									"type": "string",
									"description": "'OK' da die Berechnung erfolgreich war",
									"enum": [
										"OK"
									],
									"example": "OK"
								},
								"value": {
									"type": "integer",
									"description": "Tarifliche Einkommensteuer, abgerundet auf den nächsten vollen Euro",
									"minimum": 0,
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->ESt . "\n";
?>
								}
							}
						}
					},
					"400": {
						"description": "Fehler",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"calcESt"
									],
									"example": "calcESt"
								},
								"result": {
									"type": "string",
									"description": "'Error', da ein Fehler aufgetreten ist",
									"enum": [
										"Error"
									],
									"example": "Error"
								},
								"errors": {
									"type": "string",
									"description": "Liste der aufgetretenen Fehler",
									"example": ""
								}
							}
						}
					}
				}
			}
		},
		"/methods/calcSoli": {
			"post": {
				"summary": "Berechnung des Solidaritätszuschlags",
				"parameters": [
					{
						"in": "body",
						"name": "body",
						"description": "Angaben zur Berechnung",
						"schema": {
							"type": "object",
							"required": [
								"year",
								"splitting",
								"ESt"
							],
							"properties": {
								"year": {
									"type": "integer",
									"description": "Jahr, für das die Berechnung erfolgen soll",
<?php
echo str_repeat("\t", 9) . "\"minimum\":" . $validYearsSoli->minimum . ",\n";
echo str_repeat("\t", 9) . "\"maximum\":" . $validYearsSoli->maximum . ",\n";
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->year . "\n";
?>
								},
								"splitting": {
									"type": "boolean",
									"description": "Angabe, ob das Splittingverfahren für zusammenveranlagte steuerpflichtige Personen verwendet werden soll",
<?php
echo str_repeat("\t", 9) . "\"example\":" . ($demoValues->splitting ? "true" : "false") . "\n";
?>
								},
								"ESt": {
									"type": "integer",
									"description": "Tarifliche Einkommensteuer, auf die der Solidaritätszuschlag berechent werden soll",
									"minimum": 0,
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->ESt . "\n";
?>
								}
							}
						}
					}
				],
				"responses": {
					"200": {
						"description": "Ergebnis der Berechnung",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"calcSoli"
									],
									"example": "calcSoli"
								},
								"result": {
									"type": "string",
									"description": "'OK' da die Berechnung erfolgreich war",
									"enum": [
										"OK"
									],
									"example": "OK"
								},
								"value": {
									"type": "number",
									"description": "Solidaritätszuschlag, abgerundet auf den nächsten Euro-Cent",
									"minimum": 0,
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->Soli . "\n";
?>
								}
							}
						}
					},
					"400": {
						"description": "Fehler",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"calcSoli"
									],
									"example": "calcSoli"
								},
								"result": {
									"type": "string",
									"description": "'Error', da ein Fehler aufgetreten ist",
									"enum": [
										"Error"
									],
									"example": "Error"
								},
								"errors": {
									"type": "string",
									"description": "Liste der aufgetretenen Fehler",
									"example": ""
								}
							}
						}
					}
				}
			}
		},
		"/methods/getGrundfreibetrag": {
			"post": {
				"summary": "Grundfreibetrag für Steuerjahr",
				"parameters": [
					{
						"in": "body",
						"name": "body",
						"description": "Steuerjahr",
						"schema": {
							"type": "object",
							"required": [
								"year"
							],
							"properties": {
								"year": {
									"type": "integer",
									"description": "Jahr, für das die Antwort erfolgen soll",
<?php
echo str_repeat("\t", 9) . "\"minimum\":" . $validYearsSoli->minimum . ",\n";
echo str_repeat("\t", 9) . "\"maximum\":" . $validYearsSoli->maximum . ",\n";
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->year . "\n";
?>
								}
							}
						}
					}
				],
				"responses": {
					"200": {
						"description": "Grundfreibetrag im angegebenen Steuerjahr",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"getGrundfreibetrag"
									],
									"example": "getGrundfreibetrag"
								},
								"result": {
									"type": "string",
									"description": "'OK' da die Berechnung erfolgreich war",
									"enum": [
										"OK"
									],
									"example": "OK"
								},
								"value": {
									"type": "number",
									"description": "Grundfreibetrag",
									"minimum": 0,
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->Gfb . "\n";
?>
								}
							}
						}
					},
					"400": {
						"description": "Fehler",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"getGrundfreibetrag"
									],
									"example": "getGrundfreibetrag"
								},
								"result": {
									"type": "string",
									"description": "'Error', da ein Fehler aufgetreten ist",
									"enum": [
										"Error"
									],
									"example": "Error"
								},
								"errors": {
									"type": "string",
									"description": "Liste der aufgetretenen Fehler",
									"example": ""
								}
							}
						}
					}
				}
			}
		},
		"/methods/getDemoValues": {
			"get": {
				"summary": "Beispielwerte für Einkommensteuer",
				"responses": {
					"200": {
						"description": "Beispielwerte",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"getDemoValues"
									],
									"example": "getDemoValues"
								},
								"year": {
									"type": "integer",
									"description": "Steuerjahr",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $validYearsESt->minimum . "\n";
?>
								},
								"Gfb":{
									"type":"integer",
									"description":"Grundfreibetrag für das Steuerjahr",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->zvE . "\n";
?>
								},
								"zvE":{
									"type":"integer",
									"description":"Zu versteuerndes Einkommen",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->zvE . "\n";
?>
								},
								"splitting":{
									"type":"boolean",
									"description":"Splittingverfahren verwendet",
<?php
echo str_repeat("\t", 9) . "\"example\":" . ($demoValues->splitting ? "true" : "false") . "\n";
?>
								},
								"ESt":{
									"type":"integer",
									"description":"Im Steuerjahr zu zahlende Einkommensteuer",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->ESt . "\n";
?>
								},
								"Soli":{
									"type":"integer",
									"description":"Im Steuerjahr zu zahlender Solidaritätszuschlag",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $demoValues->Soli . "\n";
?>
								}
							}
						}
					}
				}
			}
		},
		"/methods/validYearsESt": {
			"get": {
				"summary": "Verfügbare Jahre für Berechnung der Einkommensteuer",
				"responses": {
					"200": {
						"description": "Jahresbereich",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"validYearsESt"
									],
									"example": "validYearsESt"
								},
								"minimum": {
									"type": "integer",
									"description": "Erstes Jahr, für das eine Berechnung ausgeführt werden kann",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $validYearsESt->minimum . "\n";
?>
								},
								"maximum":{
									"type":"integer",
									"description":"Letztes Jahr, für das eine Berechnung ausgeführt werden kann",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $validYearsESt->maximum . "\n";
?>
								}
							}
						}
					}
				}
			}
		},
		"/methods/validYearsSoli": {
			"get": {
				"summary": "Verfügbare Jahre für Berechnung des Solidaritätszuschlags",
				"responses": {
					"200": {
						"description": "Jahresbereich",
						"schema": {
							"type": "object",
							"properties": {
								"calledMethod": {
									"type": "string",
									"description": "Rückgabe des Namens der aufgerufenen Methode zur Kontrolle",
									"enum": [
										"validYearsESt"
									],
									"example": "validYearsESt"
								},
								"minimum": {
									"type": "integer",
									"description": "Erstes Jahr, für das eine Berechnung ausgeführt werden kann",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $validYearsSoli->minimum . "\n";
?>
								},
								"maximum":{
									"type":"integer",
									"description":"Letztes Jahr, für das eine Berechnung ausgeführt werden kann",
<?php
echo str_repeat("\t", 9) . "\"example\":" . $validYearsSoli->maximum . "\n";
?>
								}
							}
						}
					}
				}
			}
		}
	}
}
