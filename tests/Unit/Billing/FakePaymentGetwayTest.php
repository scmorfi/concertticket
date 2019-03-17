<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Billing\FakePaymentGeteway;
use App\Billing\PaymentFailedException;
use \Carbon\Carbon;
use App\Concert;
class FakePaymentGetwayTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function charges_with_a_valid_payment_token_are_successful(){

        $paymenetGateway = new FakePaymentGeteway;

        $paymenetGateway->charge(2500,$paymenetGateway->getValidTestTocken());

        $this->assertEquals(2500,$paymenetGateway->totalCharges());

    }
    /** @test */
    function chrages_with_an_invalid_payment_token_faild(){

    	try {

    		$paymenetGateway = new FakePaymentGeteway;

        	$paymenetGateway->charge(2500,"inavlid-payment-token");
        	
        	$this->assertTrue(false);

    	}catch(PaymentFailedException $e){
    		$this->assertTrue(true);
    	}

        

    }
    
}
