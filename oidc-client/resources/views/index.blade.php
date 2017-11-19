
@extends('layout')

@section('title', 'OAuth home')

@section('content')

	<div class='panel panel-default'>
		<div class='panel-heading'>
			Login
		</div>
		<div class='panel-body'>
			<a href="/oidc/discover" class="btn btn-primary">
				Auth0 discovery
			</a>
			<a href="/login/oidc/oauth0" class="btn btn-primary">
				Login with OIDC
			</a>
			<a href="/login/occ/oauth0" class="btn btn-primary">
				Login with openid-connect-client
			</a>
			<a href="/login/oidc/local/redirect" class="btn btn-primary">
				Login with local server
			</a>
		</div>

		
	</div>

	@if(Session::has('error'))
		<div class="alert alert-danger" role="alert">{{Session::get('error')}}</div>
	@endif

	@if(Session::has('userInfo'))
		<div class='panel panel-default'>
			<div class='panel-heading'>
				Oidc client - Auth0
			</div>
			<div class='panel-body'>
			<pre>
{{ json_encode(Session::get('userInfo'),JSON_PRETTY_PRINT) }}
			</pre>

			</div>
		</div>
	@endif

	@if(Session::has('userInfo2'))
		<div class='panel panel-default'>
			<div class='panel-heading'>
				openid-connect-client - Auth0
			</div>
			<div class='panel-body'>
			<pre>
{{ json_encode(Session::get('userInfo2'),JSON_PRETTY_PRINT) }}
			</pre>

			</div>
		</div>
	@endif

	@if(Session::has('discoveryInfo'))
	<div class='panel panel-default'>
		<div class='panel-heading'>
			Discovery Info
		</div>
		<div class='panel-body'>			
			<pre>
{{ json_encode(Session::get('discoveryInfo'),JSON_PRETTY_PRINT) }}
			</pre>

		</div>
	</div>
	@endif



@endsection