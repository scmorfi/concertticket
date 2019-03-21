<?php

namespace App;

class reservation{

	private $tickets;

	public function __construct($tickets){
		$this->tickets = $tickets;
	}

	public function totalCost(){
		return $this->tickets->sum('price');
	}
}