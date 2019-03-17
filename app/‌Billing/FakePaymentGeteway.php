<?php 

namespace App\Billing;
use App\Billing\PaymentGeteway;
use App\Billing\PaymentFailedException;

class FakePaymentGeteway implements PaymentGeteway{
	private $charges;
	public function __construct(){
		$this->charges = collect();
	}
	public function getValidTestTocken(){
		return "valid-token";
	}

	public function charge($amount, $token){
		if($token!==$this->getValidTestTocken())
			throw new PaymentFailedException;
		$this->charges[] = $amount;
	}
	public function totalCharges(){
		return $this->charges->sum();
	}
}