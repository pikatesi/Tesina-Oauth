
@extends('layout')

@section('title', 'OAuth home')

@section('content')

	<div class='panel panel-default'>
		<div class='panel-heading'>
			Login
		</div>
		<div class='panel-body'>
			<a href="/login/google" class="btn btn-primary">
				Login with google
			</a>
			<a href="/login/fb" class="btn btn-primary">
				Login with facebook
			</a>
			<a href="/login/passport" class="btn btn-primary">
				Login with Passport
			</a>
		</div>

		
	</div>

	@if(Session::has('error'))
	<div class="alert alert-danger" role="alert">{{Session::get('error')}}</div>
	@endif
	<div class="row">
	<div class="col-md-4">
	@if(Session::has('google'))
	<div class='panel panel-default'>
		<div class='panel-heading'>
			Google User info
		</div>
		<div class='panel-body'>			
			<pre>
{{ json_encode(Session::get('google'),JSON_PRETTY_PRINT) }}
			</pre>

		</div>
	</div>
	@endif
</div>
<div class="col-md-4">
	@if(Session::has('fb-token'))
	<div class='panel panel-default'>
		<div class='panel-heading'>
			Facebook User info
		</div>
		<div class='panel-body'>
			<h3>User</h3>
			<pre>
{{ json_encode(Session::get('fb-user'),JSON_PRETTY_PRINT) }}
			</pre>			
			<h3>Token</h3>
			<pre>
{{ json_encode(Session::get('fb-token')->getValue(),JSON_PRETTY_PRINT) }}
{{ print_r(Session::get('fb-token'))}}
			</pre>			
			<pre>

			</pre>

		</div>
	</div>
	
	@endif
</div>
<div class="col-md-4">
	@if(Session::has('passport-token'))
	<div class='panel panel-default'>
		<div class='panel-heading'>
		Passport User info
		</div>
		<div class='panel-body'>
			<h3>Token</h3>
			<pre>
{{ json_encode(Session::get('passport-token'),JSON_PRETTY_PRINT) }}
			</pre>			
			<h3>User</h3>
			<pre>
{{ json_encode(Session::get('passport-response'),JSON_PRETTY_PRINT) }}
			</pre>			
		</div>
	</div>
	
	@endif
</div>
</div>
@endsection