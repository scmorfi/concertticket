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

    /**
     @test
     */
    public function customer_can_purchase_concert_tickets()
    {
        $paymentGeteway = new FakePaymentGeteway();
        $this->app->instance(PaymentGeteway::class,$paymentGeteway);

        $concert = factory(Concert::class)->state("publiched")->create(["ticket_price" => 1500]);
        $response = $this->json("post","concert/".$concert->id."/orders",[
            "email" => "a@b.com",
            "ticket_quantity" => 3,
            "_token" => $paymentGeteway->getValidTestTocken()
        ]);
        $response->assertStatus(201);

        $this->assertEquals(4500, $paymentGeteway->totalCharges());
        $order = $concert->orders()->where("email","a@b.com")->first();
        $this->assertNotNull($order);
        $this->assertEquals(3,$order->tickets()->count());
    }
}
