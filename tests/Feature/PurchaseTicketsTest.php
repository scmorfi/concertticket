<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Concert;
use App\Billing\FakePaymentGeteway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Response;
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
        
        // $this->disableExceptionHandling();

        $concert = factory(Concert::class)->state("publiched")->create(["ticket_price" => 1500]);
        $concert->addTickets(3);
        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "ticket_quantity" => 3,
            "_token" => $this->paymentGeteway->getValidTestTocken()
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            "email" => "a@b.com",
            "ticket_quantity" => 3,
            "amount"  => 4500


        ]);

        $this->assertEquals(4500, $this->paymentGeteway->totalCharges());
        $order = $concert->orders()->where("email","a@b.com")->first();
        $this->assertNotNull($order);
        $this->assertEquals(3,$order->tickets()->count());
    }
    /**
     @test
     */
    public function an_orders_is_not_created_if_payments_failds()
    {
      

        $concert = factory(Concert::class)->state("publiched")->create(["ticket_price" => 1500]);
        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "ticket_quantity" => 3,
            "_token" => "inavalid-payment-token"
        ]);
        // $response->assertStatus(422);

        $order = $concert->orders()->where("email","a@b.com")->first();
        $this->assertNull($order);
    }



    /** @test */
    public function cannot_purchase_tickets_to_an_unpubliched_consert(){

        $concert = factory(Concert::class)->state("unpubliched")->create();
        $concert->addTickets(3);

        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "ticket_quantity" => 3,
            "_token" => "inavalid-payment-token"
        ]);
        $response->assertStatus(404);
        $this->assertEquals(0,$concert->orders()->count());
        $this->assertEquals(0, $this->paymentGeteway->totalCharges());

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

    /** @test */
    public function cannot_purchase_more_tickets_than_remain(){

        // $this->disableExceptionHandling();

        $concert = factory(Concert::class)->state('publiched')->create([
            'total_tickets_available' => 50
        ]);
        $concert->addTickets(50);

        $response = $this->orderTickets($concert,[
            "email" => "a@b.com",
            "ticket_quantity" => 51,
            "_token" => $this->paymentGeteway->getValidTestTocken()
        ]);

        $response->assertStatus(422);
        $orders = $concert->orders()->where("email", "a@b.com")->first();

        $this->assertNull($orders);
        $this->assertEquals(0, $this->paymentGeteway->totalCharges());
        $this->assertEquals(50, $concert->ticketsRemaining());


    }
}
