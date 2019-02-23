<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class ViewConcertListingTest extends TestCase
{
    use DatabaseMigrations;

    /**
     @test
     */
    public function user_can_view_unpubliched_concert_listings()
    {
        $concert = factory(Concert::class)->state("unpubliched")->create();
        // dd($concert);
        $response = $this->get('/concert/'.$concert->id);
        // dd($response);
        $response->assertStatus(404);
    }
}
