<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Concert;
use App\Billing\FakePaymentGeteway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Billing\PaymentGeteway;
class PurchaseTicketsTest extends TestCase
{
    use DatabaseMigrations;
    // private $paymentGeteway;
    protected function setUp(){
        parent::setUp();
        $this->paymentGeteway = new FakePaymentGeteway;
        $this->app->instance(PaymentGeteway::class,$this->paymentGeteway);
        
    }

    public function orderTickets($concert,$params){
        return $this->json("post","concert/".$concert->id."/orders",$params);
    }

    /**
     @test
     */
    public function customer_can_purchase_concert_tickets()
    {
      

        $concert = factory(Concert::class)->state("publiched")->create(["ticket_price" => 1500]);
        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "ticket_quantity" => 3,
            "_token" => $this->paymentGeteway->getValidTestTocken()
        ]);
        $response->assertStatus(201);

        $this->assertEquals(4500, $this->paymentGeteway->totalCharges());
        $order = $concert->orders()->where("email","a@b.com")->first();
        $this->assertNotNull($order);
        $this->assertEquals(3,$order->tickets()->count());
    }
    /**
    @test
    */
    public function email_is_required_to_purchase(){


        $concert = factory(Concert::class)->state("publiched")->create();
        $response = $this->orderTickets($concert,[
            "ticket_quantity" => 3,
            "_token" => $this->paymentGeteway->getValidTestTocken()
        ]);
        // dd($response->responce());
        $response->assertStatus(422);

        // $response->assertArrayHasKey('email',$response->decodeResponceJson());

    }
    /**
    @test
    */
    public function ticket_quantity_is_required_to_purchase(){


        $concert = factory(Concert::class)->state("publiched")->create();
        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "_token" => $this->paymentGeteway->getValidTestTocken()
        ]);
        // dd($response->responce());
        $response->assertStatus(422);

        // $response->assertArrayHasKey('email',$response->decodeResponceJson());

    }
    /**
    @test
    */
    public function ticket_quantity_must_min_1_token(){


        $concert = factory(Concert::class)->state("publiched")->create();
        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "ticket_quantity" => 0,
            "_token" => $this->paymentGeteway->getValidTestTocken()
        ]);
        // dd($response->responce());
        $response->assertStatus(422);

        // $response->assertArrayHasKey('email',$response->decodeResponceJson());

    }
    /**
    @test
    */
    public function token_is_required_to_purchase(){


        $concert = factory(Concert::class)->state("publiched")->create();
        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "ticket_quantity" => 3
        ]);
        // dd($response->responce());

        
        $response->assertStatus(422);

        // $response->assertArrayHasKey('email',$response->decodeResponceJson());

    }
}
