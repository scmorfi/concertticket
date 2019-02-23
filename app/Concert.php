<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    protected $guarded = [];
    
    public function getFormattedDateAttribute(){
    	return $this->date->format('F j, Y');
    }
    public function getFormattedStartTimeAttribute(){
    	return $this->date->format('g:ia');
    }
    public function orders(){
    	return $this->hasMany(Order::class);
    }
    public function scopePubliched($query){
    	return $query->whereNotNull('publiched_at');
    }
    public function orderTIckets($email,$ticketQuantity){
        $order = $this->orders()->create(["email" => $email]);

        foreach (range(1,$ticketQuantity) as $i) {
            $order->tickets()->create([]);
        }
        return $order;
    }
}
