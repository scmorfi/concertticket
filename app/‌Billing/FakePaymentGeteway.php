<?php 

namespace App\Billing;
use App\Billing\PaymentGeteway;

class FakePaymentGeteway implements PaymentGeteway{
	private $charges;
	public function __construct(){
		$this->charges = collect();
	}
	public function getValidTestTocken(){
		return "valid-token";
	}
	public function charge($amount, $token){
		$this->charges[] = $amount;
	}
	public function totalCharges(){
		return $this->charges->sum();
	}
}