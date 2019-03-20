<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Concert;
use App\Order;
class TicketTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function a_ticket_can_be_released(){

		$concert = factory(Concert::class)->state("publiched")->create(["ticket_price" => 1500]);

                $concert->addTickets(1);

                $order = $concert->orderTickets("a@b.com",1);

                $ticket = $order->tickets()->first();

                $this->assertEquals($order->id,$ticket->order_id);

                $ticket->release();

                $this->assertNull($ticket->fresh()->order_id);

	}




}