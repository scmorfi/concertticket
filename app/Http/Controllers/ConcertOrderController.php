<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Billing\PaymentGeteway;
use App\Concert;
class ConcertOrderController extends Controller
{
	private $paymentGeteway;
	
	public function __construct(PaymentGeteway $paymentGeteway){
		$this->paymentGeteway = $paymentGeteway;
	}
    public function store($concertId,Request $request){
    	$this->validate(request(),[
    		"email" => "required",
            "ticket_quantity" => "integer|required|min:1",
            "_token" => "required"
    	]);
    	$concert = Concert::find($concertId);
    	
    	
    	$this->paymentGeteway->charge($request->ticket_quantity * $concert->ticket_price,$request->_token);
    	$order = $concert->orderTickets($request->email,$request->ticket_quantity);

    	return response()->json([],201);
    }
}
