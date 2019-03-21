<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGeteway;
use App\Billing\PaymentFailedException;
use App\Exceptions\NotEnoughTicketsException;
use App\Concert;
use App\Order;
use App\Reservation;
class ConcertOrderController extends Controller
{
	private $paymentGeteway;
	
	public function __construct(PaymentGeteway $paymentGeteway){
		$this->paymentGeteway = $paymentGeteway;
	}
    public function store($concertId,Request $request){
        
        $concert = Concert::publiched()->findOrFail($concertId);

    	$this->validate(request(),[
    		"email" => "required",
            "ticket_quantity" => "integer|required|min:1",
            "_token" => "required"
    	]);
        try {
            // find some tickets
            $tickets = $concert->findTickets($request->ticket_quantity);


            $reservation = new Reservation($tickets);

            //charge the customer for the tickets
            $this->paymentGeteway->charge($reservation->totalCost(),$request->_token);
            
            //create an order for those tickets
            $order = Order::forTickets($tickets,$request->email,$reservation->totalCost());


        	

            
        	return response()->json($order,201);
        	
        }catch(PaymentFailedException $e){
            // $order->delete();
            return response()->json([],422);
        }
        catch(NotEnoughTicketsException $e){
            return response()->json([],422);
        }

        
    	
    }
}
