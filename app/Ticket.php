<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	protected $guarded = [];

    public function scopeAvailable($query){

    	return $query->whereNull("order_id");

    }

    public function release(){
    	$this->update(["order_id" => null]);
    }
    public function concert(){
    	return $this->belongsTo(Concert::class);
    }
    public function getpriceAttribute(){
    	return $this->concert->ticket_price;
    }
}
