<?php

class estZoneLimits {
	public function __construct($z0v, $z1v, $z2v, $z3v) {
		$this->zone0 = $z0v; // Grundfreibetrag 
		$this->zone1 = $z1v; // Untere Progressionszone
		$this->zone2 = $z2v; // Obere Progressionszone
		$this->zone3 = $z3v; // 1. Proportionalzone
	}
}

?>