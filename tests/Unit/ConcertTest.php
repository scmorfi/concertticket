<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Exceptions\NotEnoughTicketsException;
use \Carbon\Carbon;
use App\Concert;
class ConcertTest extends TestCase
{
	use DatabaseMigrations;
	
    /** @test */
    function can_get_formated_date(){
    	$concert = factory(Concert::class)->create([
    			'date' => Carbon::parse('2016-12-1 8:00pm')
    	]);
    	// dd($concert);
    	$date = $concert->formatted_date;
    	$this->assertEquals('December 1, 2016',$date);


    }
    /** @test */
    function can_get_formated_start_time(){
    	$concert = factory(Concert::class)->create([
    			'date' => Carbon::parse('2016-12-1 17:00:00')
    	]);
    	// dd($concert);
    	$time = $concert->formatted_start_time;
    	$this->assertEquals('5:00pm',$time);


    }
    /** @test */
    function concert_with_a_publiched_at_date_are_publiched(){
    	$concertA = factory(Concert::class)->state("publiched")->create();
    	$concertB = factory(Concert::class)->state("publiched")->create();
    	$unpublicheConcert = factory(Concert::class)->state("unpubliched")->create();;
    	$publichedConcert = Concert::publiched()->get();
    	$this->assertTrue($publichedConcert->contains($concertA));
    	$this->assertTrue($publichedConcert->contains($concertB));
    	$this->assertFalse($publichedConcert->contains($unpublicheConcert));
    }

    /** @test */

    public function can_order_concert_tickets(){

        $concert = factory(Concert::class)->create();

        $concert->addTickets(3);

        $order = $concert->orderTickets("a@b.com", 3);
        

        $this->assertEquals("a@b.com",$order->email);

        $this->assertEquals(3, $order->tickets()->count());



    }

    /** @test */
    function can_add_tickets(){
        $concert = factory(Concert::class)->create();

        $concert->addTickets(50);

        $this->assertEquals(50,$concert->ticketsRemaining());

    }

    /** @test */
    public function tickets_remaining_does_not_include_tickets_assocated_with_an_order(){

        $concert = factory(Concert::class)->create();

        $concert->addTickets(50);

        $concert->orderTickets("a@b.com", 30);

        $this->assertEquals(20, $concert->ticketsRemaining());
    }

    /** @test */
    public function trying_to_purchase_more_tickets_than_reamin_throws_an_exception(){

        $concert = factory(Concert::class)->create();

        $concert->addTickets(10); 
        
        try{
            $concert->orderTickets("a@b.com", 11);
        }catch(NotEnoughTicketsException $e){

            $order = $concert->orders()->where('email','a@b.com')->first();
            $this->assertNull($order);
            $this->assertEquals(10, $concert->ticketsRemaining());
            return;
        }    

        $this->fail("Order successed even there were not enough tickets remaining");
    }

    /** @test */
    public function cannot_order_tickets_that_have_already_been_purchased(){

        $concert = factory(Concert::class)->create();

        $concert->addTickets(10); 

        $concert->orderTickets("x@y.com", 8);
        
        try{
            $concert->orderTickets("a@b.com", 3);
        }catch(NotEnoughTicketsException $e){

            $abOrder = $concert->orders()->where('email','a@b.com')->first();
            $this->assertNull($abOrder);
            $this->assertEquals(2, $concert->ticketsRemaining());
            return;
        }    

        $this->fail("Order successed even there were not enough tickets remaining");

    }
}
