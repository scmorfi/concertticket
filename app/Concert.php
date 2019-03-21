<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Exceptions\NotEnoughTicketsException;
use App\Order;
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
    	return $this->BelongsToMany(Order::class,'tickets');
    }
    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
    public function scopePubliched($query){
    	return $query->whereNotNull('publiched_at');
    }
    public function orderTickets($email,$ticketQuantity){
        

        $tickets = $this->findTickets($ticketQuantity);

        
        return $this->createOrder($email,$tickets);
    }
    public function findTickets($quantity){
        $tickets = $this->tickets()->available()->take($quantity)->get();;
        if($tickets->count() < $quantity){
            throw new NotEnoughTicketsException;
            
        }
        return $tickets;
    }
    public function createOrder($email,$tickets){
        return Order::forTickets($tickets,$email,$tickets->sum('price'));
    }
    public function addTickets($quantity){
        foreach (range(1,$quantity) as $i) {
            $this->tickets()->create([]);
        }
    }
    public function ticketsRemaining(){
        return $this->tickets()->available()->count();
    }
    
}
