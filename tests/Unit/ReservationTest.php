<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Concert;
use App\Reservation;
class ReservationTest extends TestCase
{
	use DatabaseMigrations;


	/** @test */

	public function calculating_the_total_cost(){

		// $concert = factory(Concert::class)->create(["ticket_price" => 1200]);

		// $concert->addTickets(3);

		// $tickets = $concert->findTickets(3);

		$tickets = collect([
			(object) ["price" => 1200],
			(object) ["price" => 1200],
			(object) ["price" => 1200]
		]);

		$reservation = new Reservation($tickets);

		$this->assertEquals(3600,$reservation->totalCost());

	}


}