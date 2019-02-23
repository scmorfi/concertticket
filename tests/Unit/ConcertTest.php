<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
}
