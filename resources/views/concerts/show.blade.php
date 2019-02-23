@extends('layouts.app')

@section('content')
<h1>{{$concert->title}}</h1>
<form id="purchase">
	price : <input name="price" >
	email : <input name="email">
	card number : <input name="card">
	date : <input name="date" >
	cvv2 : <input name="cvv2">
</form>
@endsection

@section('scripts')
<script type="text/javascript">
  //   $(document).ready(function(){
  //   	$.ajax({
		//   method: "POST",
		//   url: "some.php",
		//   data: { name: "John", location: "Boston" }
		// })
		// .done(function( msg ) {
		//   alert( "Data Saved: " + msg );
		// });
  //   });
</script>
@append

