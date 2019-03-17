<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGeteway;
use App\Billing\PaymentFailedException;
use App\Exceptions\NotEnoughTicketsException;
use App\Concert;
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
        	$order = $concert->orderTickets($request->email,$request->ticket_quantity);
        	$this->paymentGeteway->charge($request->ticket_quantity * $concert->ticket_price,$request->_token);
        	
        }catch(PaymentFailedException $e){
            return response()->json([],422);
        }
        catch(NotEnoughTicketsException $e){
            return response()->json([],422);
        }

        return response()->json([],201);
    	
    }
}
