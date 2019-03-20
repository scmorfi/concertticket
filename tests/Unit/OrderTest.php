<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Concert;
use App\Order;
class OrderTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function tickets_are_released_when_an_order_is_cancelled(){

		$concert = factory(Concert::class)->create();

                $concert->addTickets(10);

                $order = $concert->orderTickets("a@b.com",3);

                $this->assertEquals(7,$concert->ticketsRemaining());

                $order->cancel();

                $this->assertEquals(10,$concert->ticketsRemaining());

                $this->assertNull(Order::find($order->id));

	}
        /** @test */
        public function converting_to_an_array(){
                $concert = factory(Concert::class)->create(["ticket_price" => 1500]);

                $concert->addTickets(5);

                $order = $concert->orderTickets("a@b.com",5);

                $result = $order->toArray();

                $this->assertEquals([
                        "email" => "a@b.com",
                        "ticket_quantity" => 5,
                        "amount" => 7500
                ],$result);


        }




}