@extends('layouts.app')

@section('content')
<h1>{{$concert->title}}</h1>
<form id="purchase" method="post" action="{{URL::route('concertOrder.store',$concert->id)}}" >
	<input value="{{ csrf_token() }}" name="_token" type="hidden" />
	email : <input name="email">
	counter : <input name="ticket_quantity" >
	<button >ارسال</button>
</form>
@endsection

@section('scripts')
<script type="text/javascript">
   //  $(document).ready(function(){
   //  	$("#purchase").submit(function(e){
   //  		e.preventDefault();
   //  		$.ajax({
			//   method: "POST",
			//   url: ,
			//   data: $("#purchase").serialize();
			// })
			// .done(function( msg ) {
			//   alert( "Data Saved: " + msg );
			// });
   //  	});
    	
   //  });
</script>
@append

