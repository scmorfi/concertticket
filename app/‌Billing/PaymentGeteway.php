<?php

namespace App\Billing;

interface PaymentGeteway {
	public function charge($amount,$token);
}